<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        #$this->call(DeviceHistoryTableSeeder::class);
        #$this->call(DeviceTableSeeder::class);
        $this->call(UserRolesSeeder::class);
        $this->call(ThresholdTableSeeder::class);
    }
}
