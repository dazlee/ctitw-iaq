<?php

use Illuminate\Database\Seeder;
use App\Device;

class DeviceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $created_at = $updated_at = date('Y-m-d G:i:s');
        for ($i = 1; $i <= 16; $i++) {
            Device::insert([
                'id' => "A001-{$i}",
                'created_at' => $created_at,
                'updated_at' => $updated_at
            ]);
        }
    }
}
