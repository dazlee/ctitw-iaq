<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceHistory extends Model
{
    protected $table = 'device_history';
    protected $fillable = ['device_id', 'co2', 'temp', 'rh', 'created_at'];

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
		        'device_id' => $fields[0],
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

    public static function doSummary($query) {
        return [
            'co2' => ['max' => $query->max('co2'), 'avg' => $query->avg('co2'), 'min' => $query->min('co2')],
            'temp' => ['max' => $query->max('temp'), 'avg' => $query->avg('temp'), 'min' => $query->min('temp')],
            'rh' => ['max' => $query->max('rh'), 'avg' => $query->avg('rh'), 'min' => $query->min('rh')]
        ];
    }

    public static function doAvg($query) {
        return ['co2' => $query->avg('co2'), 'temp' => $query->avg('temp'), 'rh' => $query->avg('rh')];
    }

    public function scopeBetweenDatesOfDeviceId($query, $deviceId, $fromDate, $toDate) {
        $fromDate = date_create($fromDate)->setTime(00, 00, 00);
        $toDate = date_create($toDate)->setTime(23, 59, 59);
        return $query->where('device_id', $deviceId)->whereBetween('record_at', array($fromDate, $toDate))->orderBy('record_at', 'asc');
    }

    public function scopeDescOrder($query) {
        return $query->orderBy('record_at', 'desc');
    }

    public function scopeDescOrderOfDeviceId($query, $deviceId) {
        return $query->where('device_id', $deviceId)->orderBy('record_at', 'desc');
    }

    public function scopeOneWeek($query) {
        $date = new \DateTime();
        $datetime = $date->modify('-6 day')->format('Y-m-d');
        return $query->whereDate('record_at', '>', $datetime)->orderBy('record_at', 'asc');
    }

    public function scopeOneWeekOfDeviceId($query, $deviceId) {
        $date = new \DateTime();
        $datetime = $date->modify('-6 day')->format('Y-m-d');
        return $query->where('device_id', $deviceId)->whereDate('record_at', '>', $datetime)->orderBy('record_at', 'asc');
    }
}
