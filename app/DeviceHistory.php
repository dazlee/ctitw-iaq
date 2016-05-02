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
}
