<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $fillable = ['user_id', 'admin_id', 'phone'];
    public $timestamps = false;

    public function clients() {
        return $this->hasMany(Client::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
