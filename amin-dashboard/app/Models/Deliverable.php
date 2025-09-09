<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deliverable extends Model
{

    protected $fillable = [
        'assignment_id',
        'content',
        'submitted_on',
        'status',
        'file_path',
        'file_name',
    ];

    // Define relationships with other models
    public function assignment(){

        return $this->belongsTo(Assignment::class);
    }

    public function statusUpdate(){

        return $this->hasOne(StatusUpdate::class);
    }
}