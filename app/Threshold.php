<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Threshold extends Model
{
    protected $table = 'threshold';
    protected $fillable = ['co2', 'temp', 'rh', 'user_id'];
    public $timestamps = false;
}
