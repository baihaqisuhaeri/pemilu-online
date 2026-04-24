<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

//#[Fillable(['name', 'nik', 'email', 'password', 'role', 'has_voted'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{

protected $fillable = [
    'name',
    'nik',
    'email',
    'password',
    'role',
    'voted_presiden',
    'voted_dpr',
    'voted_dpd',
];
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'voted_presiden'    => 'boolean',
            'voted_dpr'         => 'boolean',
            'voted_dpd'         => 'boolean',
        ];
    }
}
