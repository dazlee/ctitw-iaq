<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_history', function (Blueprint $table) {
            $table->increments('id');
            $table->string('device_id');
            $table->float('co2');
            $table->float('temp');
            $table->float('rh');
            $table->dateTime('record_at');
            $table->timestamps();
            $table->unique(['device_id', 'record_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_history');
    }
}
