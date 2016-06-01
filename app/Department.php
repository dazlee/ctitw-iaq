<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['user_id', 'client_id', 'device_id', 'phone'];
    public $timestamps = false;

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function client() {
        return $this->hasOne(Client::class, 'user_id', 'client_id');
    }
}
