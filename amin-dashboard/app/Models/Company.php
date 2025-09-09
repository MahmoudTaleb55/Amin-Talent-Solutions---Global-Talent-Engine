<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'contact_person',
        'phone',
        'address',
        'industry',
        'company_size',
        'description',
    ];

    // Define relationships with other models
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobRequests()
    {
        return $this->hasMany(JobRequest::class);
    }
}
