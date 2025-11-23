<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @mixin \Spatie\Permission\Traits\HasRoles
 * @method bool hasRole(string|array|\Spatie\Permission\Contracts\Role $roles)
 * @method bool hasAnyRole(string|array|\Spatie\Permission\Contracts\Role ...$roles)
 * @method bool hasAllRoles(string|array|\Spatie\Permission\Contracts\Role ...$roles)
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // legacy enum; will be deprecated after full migration to Spatie
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Supported role names for initial seeding & validation.
     *
     * @return array
     */
    public static function getRoles()
    {
        return ['admin', 'ceo', 'project_manager', 'company', 'employee', 'freelancer'];
    }

    /**
     * Get the company associated with the user.
     */
    public function company()
    {
        return $this->hasOne(Company::class);
    }

    /**
     * Get the freelancer profile associated with the user.
     */
    public function freelancer()
    {
        return $this->hasOne(Freelancer::class);
    }
}