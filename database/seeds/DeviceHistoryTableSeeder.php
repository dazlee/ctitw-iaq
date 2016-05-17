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
        $begin = new DateTime( '2016-05-10' );
        $end = new DateTime();
        $interval = new DateInterval('PT10M');
        $daterange = new DatePeriod($begin, $interval ,$end);
        $created_at = $updated_at = date('Y-m-d G:i:s');

        foreach($daterange as $date) {
            $record_at = $date->format("Y-m-d G:i:00");
            for ($i = 1; $i <= 16; $i++) {
                DeviceHistory::insert([
                    'device_id' => $i,
                    'co2' => rand()%100,
                    'temp' => rand()%50,
                    'rh' => rand()%100,
                    'record_at' => $record_at,
                    'created_at' => $created_at,
                    'updated_at' => $updated_at
                ]);
            }
        }
    }
}
