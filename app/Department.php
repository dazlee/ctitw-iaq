<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['user_id', 'client_id', 'device_id', 'phone'];
    public $timestamps = false;
}
