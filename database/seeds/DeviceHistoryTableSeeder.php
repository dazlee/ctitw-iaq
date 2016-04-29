<?php

use Illuminate\Database\Seeder;
use App\DeviceHistory;

class DeviceHistoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	$begin = new DateTime( '2016-03-01' );
	$end = new DateTime( '2016-03-31' );
	$interval = new DateInterval('P1D');
	$daterange = new DatePeriod($begin, $interval ,$end);
	$updated_at = date('Y-m-d h:i:s');

	foreach($daterange as $date){
	    $created_at = $date->format("Y-m-d 12:00:00");
	    for ($i = 1; $i <= 16; $i++) {
		DeviceHistory::insert([
		    'device_id' => $i,
		    'co2' => rand()%1000,
		    'temp' => rand()%50,
		    'rh' => rand()%100,
		    'created_at' => $created_at,
		    'updated_at' => $updated_at,	
		]);
	    }
	}
    }
}
