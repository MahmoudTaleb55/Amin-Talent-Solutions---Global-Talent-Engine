<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobRequest extends Model
{

    protected $fillable = [
        'company_id',
        'description',
        'deadline',
        'status',
    ];

    // Define relationships with other models

    public function company(){

        return $this->belongsTo(Company::class);
    }

    public function assignments(){

        return $this->hasMany(Assignment::class);
    }
}