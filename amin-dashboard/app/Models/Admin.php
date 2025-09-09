<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{

    protected $fillable = [
        'user_id',
        'admin_level',
        'permissions',
    ];

    protected $casts = [
        'permissions' => 'array',
    ];

    // Define relationships with other models

    public function user(){

        return $this->belongsTo(User::class);
    }

}
