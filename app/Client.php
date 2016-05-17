<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['id', 'phone'];
    public $timestamps = false;

    public function departments() {
        return $this->hasMany(Department::class);
    }
}
