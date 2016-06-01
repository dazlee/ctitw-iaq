<?php

use Illuminate\Database\Seeder;
use App\Threshold;

class ThresholdTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Threshold::insert([
            'co2' => 1000,
            'temp' => 30,
            'rh' => 90,
        ]);
    }
}
