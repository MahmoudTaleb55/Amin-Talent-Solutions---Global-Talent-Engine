<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectRequest extends Model
{
    protected $fillable = [
        'company_id',
        'title',
        'description',
        'requirements',
        'budget',
        'deadline',
        'status',
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'deadline' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
