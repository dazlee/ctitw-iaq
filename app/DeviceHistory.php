<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Threshold;
use Mail;
use App\Client;
use App\Department;
use App\User;
use Log;

class DeviceHistory extends Model
{
    protected $table = 'device_history';
    protected $fillable = ['device_id', 'co2', 'temp', 'rh', 'created_at'];

    public static $checkItems = ['co2', 'temp', 'rh'];
    public static $co2Pattern = '/CO2\((\-?[0-9]+(\.\d{1,2})?) ppm\)/';
    public static $tempPattern = '/temp\((\-?[0-9]+(\.\d{1,2})?)\)/';
    public static $rhPattern = '/rh\((\-?[0-9]+(\.\d{1,2})?) %\)/';

    public static function parseContent($deviceAccount, $content) {
        $rows = [];
	    $lines = explode("\n", $content);
	    $created_at = $updated_at = date('Y-m-d G:i:s');

	    foreach ($lines as $line) {
	        $fields = explode('/', $line);

	        if (count($fields) !== 9)
		        continue;

            $co2 = (preg_match(self::$co2Pattern, $fields[6], $matched) !== False) ? (float)$matched[1] : 0;
            $temp = (preg_match(self::$tempPattern, $fields[7], $matched) !== False) ? (float)$matched[1] : 0;
            $rh = (preg_match(self::$rhPattern, $fields[8], $matched) !== False) ? (float)$matched[1] : 0;

            $co2 = $co2 > 3000 ? 3000 : $co2;
            $co2 = $co2 < 0 ? 0 : $co2;
            $temp = $temp > 40 ? 40 : $temp;
            $temp = $temp < 0 ? 0 : $temp;
            $rh = $rh > 100 ? 100 : $rh;
            $rh = $rh < 0 ? 0 : $rh;
	        $rows[] = [
		        'device_id' => $deviceAccount . '-' . (int)$fields[0],
		        'co2' => $co2,
		        'temp' => $temp,
		        'rh' => $rh,
		        'record_at' => sprintf("%s-%s-%s %s:%s:00", $fields[1], $fields[2], $fields[3], $fields[4], $fields[5]),
                'created_at' => $created_at,
                'updated_at' => $updated_at
	        ];
	    }

	    return $rows;
    }

    public static function sendMalfunctionMail ($deviceAccount, $rows) {
        $client = Client::where('device_account', '=', $deviceAccount)->first();
        if (!count($client)) {
            return Null;
        }

        $body = "";
        $clientName = $client->user['name'];
        foreach ($rows as $row) {
            $index = explode('-', $row['device_id'])[1];
            $device = Device::where("client_id", "=", $client->user_id)->where("index", "=", $index)->first();
            if (!isset($device)) {
                continue;
            }
            $deviceHistories = DeviceHistory::where("device_id", "=", $row['device_id'])->orderBy('record_at', 'desc')->take(13)->get();
            $count = 0;
            foreach ($deviceHistories as $deviceHistory) {
                if ($deviceHistory->co2 == $row['co2'] &&
                    $deviceHistory->temp == $row['temp'] &&
                    $deviceHistory->rh == $row['rh']) {
                        $count++;
                    } else {
                        break;
                    }
            }
            error_log("count==". $count);
            $time = "";
            switch ($count) {
                case 3:
                    $time = "30分鐘";
                    break;
                case 6:
                    $time = "一小時";
                    break;
                case 9:
                    $time = "一小時30分鐘";
                    break;
                case 12:
                    $time = "兩小時";
                    break;
            }
            if (!empty($time)) {
                $deviceName = $device->name;
                $body .= sprintf('%s - 儀器 %s(%s) %s內數據可能有異常。</br>', $clientName, $deviceName, $row['device_id'], $time);
            }
        }

        if (!empty($body)) {
            $clientEmail = $client->user['email'];
            $agentEmail = $client->agent->user['email'];
            $adminEmail = "dannytsai0905@gmail.com";//$client->agent->admin['email'];
            Mail::send('emails.warning', ['to' => $clientEmail, 'body' => $body], function ($message) use ($clientEmail, $agentEmail, $adminEmail, $body) {
                $message->to($clientEmail)->cc($agentEmail)->cc($adminEmail)->cc("daz.lee1987@gmail.com");
                $message->subject('儀器數據異常警報')->setBody($body);
            });
        }
    }

    public static function sendMail($deviceAccount, $rows) {
        $client = Client::where('device_account', '=', $deviceAccount)->first();
        if (!count($client)) {
            return Null;
        }


        $threshold = Threshold::where("user_id", "=", $client->user_id)->first();
        if (!count($threshold)) {
            $threshold = Threshold::first();
            if (!count($threshold)) {
                return Null;
            }
        }

        $adminEmail = "dannytsai0905@gmail.com";//Role::where("name", "=", "admin")->first()->users()->first()->email;
        $subjects = [];
        $body = "";

        foreach ($rows as $row) {
            $msg = Null;

            foreach (self::$checkItems as $item) {
                if (self::isSwitchedToHigh($row['device_id'], $item, $threshold->{$item}, $row[$item])) {
                    $msg .= sprintf('%s值(%s) 超過上限(%s)。  ', $item, $row[$item], $threshold->{$item});
                }

                if (self::isKeepingHighInOneHour($row['device_id'], $row['record_at'], $item, $threshold->{$item}, $row[$item])) {
                    $msg .= sprintf('%s值(%s) 已在一小時內連續超過上限(%s)。  ', $item, $row[$item], $threshold->{$item});
                }
            }

            if (empty($msg)) {
                continue;
            }

            $index = explode('-', $row['device_id'])[1];
            $device = Device::where("client_id", "=", $client->user_id)->where("index", "=", $index)->first();
            if (!isset($device)) {
                continue;
            }
            $deviceName = $device->name;
            $msg = sprintf('%s, 儀器：%s(%s), %s<br/>', $row['record_at'], $deviceName, $row['device_id'], $msg);
            $body .= $msg;
        }

        if(empty($body)) {
            return Null;
        }

        $clientEmail = $client->user->email;
        $departments = $client->departments;
        $departmentMails = [];

        foreach ($departments as $department) {
            $departmentMails[] = $department->user->email;
        }

	
        Mail::send('emails.warning', ['to' => $clientEmail, 'body' => $body], function ($message) use ($clientEmail, $departmentMails, $adminEmail, $body) {
            $message->to($clientEmail)->cc($adminEmail);
            foreach ($departmentMails as $departmentMail) {
                $message->cc($departmentMail);
            }
            $message->subject('超標警報')->setBody($body);
        });
	
    }

    public static function isSwitchedToHigh($deviceId, $item, $threshold, $value) {
        # if detected value small than thresh, return False
        if ($value <= $threshold) {
            return False;
        }

        $query = self::ofDevice($deviceId)->SortRecord('desc');

        if ($query->count() == 0) {
            return True;
        }

        $row = $query->first();

        # if lasted recored higher than thresh, return False
        if ($row->{$item} > $threshold) {
            return False;
        }

        return True;
    }

    public static function isKeepingHighInOneHour($deviceId, $record_at, $item, $threshold, $value) {
        # if detected value small than thresh, return False
        if ($value <= $threshold) {
            return False;
        }

        # find the last normal record time
        $query = self::ofDevice($deviceId)->SortRecord('desc')->where($item, '<=', $threshold);

        # count the number of records which are higher than theshold
        if ($query->count()) {
            $row = $query->first();
            $count = self::ofDevice($deviceId)->where('record_at', '>', $row->record_at)->count() + 1;
        } else {
            $count = self::ofDevice($deviceId)->count() + 1;
        }

        return ($count%6 == 0) ? True : False;
    }

    public function scopeOfLastHour($query, $datetime) {
        $date = new \DateTime($datetime);
        $datetime = $date->modify('-1 hour')->format('Y-m-d H:i:s');
        return $query->where('record_at', '>', $datetime);
    }

    public function scopeBetweenDates($query, $fromDate, $toDate) {
        $fromDate = date_create($fromDate)->setTime(00, 00, 00);
        $toDate = date_create($toDate)->setTime(23, 59, 59);
        return $query->whereBetween('record_at', array($fromDate, $toDate));
    }

    public function scopeOfDays($query, $days) {
        $date = new \DateTime();
        $datetime = $date->modify("-$days day")->format('Y-m-d');
        return $query->whereDate('record_at', '>', $datetime);
    }

    public function scopeSortRecord($query, $order) {
        return $query->orderBy('record_at', $order);
    }

    public function scopeOfDevice($query, $deviceId) {
        return $query->where('device_id', $deviceId);
    }

    public function scopeLike($query, $deviceAccount) {
        return $query->where('device_id', 'like', "{$deviceAccount}%");
    }
}
