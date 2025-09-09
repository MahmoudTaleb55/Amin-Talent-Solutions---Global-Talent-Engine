<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusUpdate extends Model
{
    protected $fillable = [
        'assignment_id',
        'status',
        'updated_on',
        'notes',
    ];

    // Define relationships with other models
    public function Deliverable()
    {
        return $this->belongsTo(Deliverable::class);
    }
}