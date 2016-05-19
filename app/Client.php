<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['user_id', 'device_account', 'agent_id', 'user_limit', 'phone'];
    public $timestamps = false;

    public function departments() {
        return $this->hasMany(Department::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
