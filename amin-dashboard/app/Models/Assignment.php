<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{

    protected $fillable = [
        'job_request_id',
        'freelancer_id',
        'company_id',
    ];

    // Define relationships with other models
    public function freelancer(){

        return $this->belongsTo(Freelancer::class);
    }

    public function jobRequest(){

        return $this->belongsTo(JobRequest::class);
    }

    public function deliverable(){

        return $this->hasOne(Deliverable::class);
    }
}