<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusUpdate extends Model
{
    protected $fillable = [
        'assignment_id',
        'status',
        'message',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
}
