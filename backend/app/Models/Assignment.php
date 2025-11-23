<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'project_request_id',
        'freelancer_id',
        'status',
        'assigned_at',
        'deadline',
        'notes',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'deadline' => 'datetime',
    ];

    public function projectRequest()
    {
        return $this->belongsTo(ProjectRequest::class);
    }

    public function freelancer()
    {
        return $this->belongsTo(Freelancer::class);
    }

    public function deliverables()
    {
        return $this->hasMany(Deliverable::class);
    }

    public function statusUpdates()
    {
        return $this->hasMany(StatusUpdate::class);
    }
}
