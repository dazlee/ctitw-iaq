<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    public $incrementing = false;
    protected $fillable = ['id', 'name', 'client_id', 'index'];

    public function client() {
        return $this->belongsTo(Client::class, 'client_id', 'user_id');
    }
}
