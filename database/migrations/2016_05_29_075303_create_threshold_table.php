<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThresholdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threshold', function (Blueprint $table) {
            $table->increments('id');
            $table->float('co2');
            $table->float('temp');
            $table->float('rh');
            $table->string('user_id')->default("");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('threshold');
    }
}
