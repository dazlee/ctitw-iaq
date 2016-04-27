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
            $table->timestamps();
	    $table->unique(['device_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('device_history');
    }
}
