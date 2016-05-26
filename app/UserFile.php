<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFile extends Model
{
    protected $fillable = ['user_id', 'file_name', 'path', 'created_at', 'updated_at'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
