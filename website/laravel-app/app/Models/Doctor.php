<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends User
{
    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}