<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Freelancer extends Model
{

    protected $fillable = [
        'user_id',
        ];

    // Define relationships with other models

    public function user(){

        return $this->belongsTo(User::class);
    }

    public function assignments(){

        return $this->hasMany(Assignment::class);
    }

}
