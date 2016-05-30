<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Threshold;
use Mail;
use App\Client;
use App\User;

class DeviceHistory extends Model
{
    protected $table = 'device_history';
    protected $fillable = ['device_id', 'co2', 'temp', 'rh', 'created_at'];
 
    public static $device_name = 'A001-';
    public static $checkItems = ['co2', 'temp', 'rh']; 
    public static $co2Pattern = '/CO2\(([0-9]+) ppm\)/';
    public static $tempPattern = '/temp\(([0-9]+)\)/';
    public static $rhPattern = '/rh\(([0-9]+) %\)/';

    public static function parseContent($content) {
        $rows = [];
	    $lines = explode("\n", $content);
	    $created_at = $updated_at = date('Y-m-d G:i:s');

	    foreach ($lines as $line) {
	        $fields = explode('/', $line);

	        if (count($fields) !== 9)
		        continue;

	        $rows[] = [
		        'device_id' => self::$device_name . (int)$fields[0],
		        'co2' => (preg_match(self::$co2Pattern, $fields[6], $matched) !== False) ? $matched[1] : -1,
		        'temp' => (preg_match(self::$tempPattern, $fields[7], $matched) !== False) ? $matched[1] : -1,
		        'rh' => (preg_match(self::$rhPattern, $fields[8], $matched) !== False) ? $matched[1] : -1,
		        'record_at' => sprintf("%s-%s-%s %s:%s:00", $fields[1], $fields[2], $fields[3], $fields[4], $fields[5]),
                'created_at' => $created_at,
                'updated_at' => $updated_at
	        ];
	    }

	    return $rows;
    }

    public static function sendMail($rows) {
        $threshold = Threshold::first();
        
        if (!count($threshold)) {
            return Null;
        }

        $subjects = [];

        foreach ($rows as $row) {
            $msg = Null;

            foreach (self::$checkItems as $item) {
                if ($row[$item] > $threshold->{$item}) {
                    $msg .= sprintf('%s(%s) is higher than threshold(%s). ', $item, $row[$item], $threshold->{$item});
                }
            }
        
            if (empty($msg)) {
                continue;
            }

            $msg = sprintf('%s, Device %s, %s<br/>', $row['record_at'], $row['device_id'], $msg);
            $client = Client::where('device_account', '=', $row['device_id'])->first();

            if (!count($client)) {
                continue;
            }

            $emails = [$client->user->email];
            $departments = $client->departments;
                        
            foreach ($departments as $department) {
                $email = $department->user->email;
                $emails[] = $email;
            }

            foreach ($emails as $email) {
                if (isset($subjects[$email])) {
                    $subjects[$email] .= $msg;
                } else {
                    $subjects[$email] = $msg;
                }
            }
        }
        
        if(empty($subjects)) {
            return Null;
        }
            
        foreach ($subjects as $to => $body) {
            Mail::send('emails.warning', ['to' => $to, 'body' => $body], function ($message) use ($to, $body) {
                $message->to($to)->subject('Warning')->setBody($body);
            });
        }
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
}
