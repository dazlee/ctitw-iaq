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
            'co2' => 200, 
            'temp' => 8, 
            'rh' => 40
        ]);
    }
}
