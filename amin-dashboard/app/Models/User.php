<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'email_verification_token',
        'email_verified_at',
        'two_factor_secret',
        'two_factor_enabled',
        'two_factor_confirmed_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Define relationships with other models
    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function freelancer()
    {
        return $this->hasOne(Freelancer::class);
    }

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }
}