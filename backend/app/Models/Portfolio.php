<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'description', 'url', 'attachments'];

    protected $casts = ['attachments' => 'array'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
