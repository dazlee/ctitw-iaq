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
        $begin = new DateTime( '2016-04-01' );
	    $end = new DateTime('2016-04-03');
	    $interval = new DateInterval('PT10M');
        $daterange = new DatePeriod($begin, $interval ,$end);
	    $created_at = $updated_at = date('Y-m-d G:i:s');
        $rows = [];

	    foreach($daterange as $date) {
            $record_at = $date->format("Y-m-d G:i:00");
            for ($i = 1; $i <= 16; $i++) {
                $rows[] = [
		            'device_id' => $i,
		            'co2' => rand()%1000,
		            'temp' => rand()%50,
                    'rh' => rand()%100,
                    'record_at' => $record_at,
		            'created_at' => $created_at,
		            'updated_at' => $updated_at,	
		        ];
	        }
        }

        DeviceHistory::insert($rows);
    }
}
