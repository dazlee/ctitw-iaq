<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Agent;

class Client extends Model
{
    protected $fillable = ['user_id', 'device_account', 'agent_id', 'user_limit', 'phone'];
    public $timestamps = false;

    public function departments() {
        return $this->hasMany(Department::class, 'client_id', 'user_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function agent() {
        return $this->belongsTo(Agent::class, 'agent_id', 'user_id');
    }
}
