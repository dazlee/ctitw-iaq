<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    public $incrementing = false;
    protected $fillable = ['id', 'name', 'client_id', 'index'];
}
