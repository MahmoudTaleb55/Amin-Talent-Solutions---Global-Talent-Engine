<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 'freelancer_id', 'company_id', 'number', 'amount', 'currency', 'description', 'status', 'meta'
    ];

    protected $casts = [
        'meta' => 'array',
    ];
}
