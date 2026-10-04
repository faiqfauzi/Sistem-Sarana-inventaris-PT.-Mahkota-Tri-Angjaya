<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Responsible;

class User extends Authenticatable
{
    use HasFactory, Notifiable;


    protected $fillable = [
        'username',
        'password',
        'role',
        'responsible_id',
    ];


    protected $hidden = [

        'password',

        'remember_token',

    ];


    protected function casts(): array
    {
        return [

            'email_verified_at' => 'datetime',

            'password' => 'hashed',

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    public function responsible()
    {
        return $this->belongsTo(
            Responsible::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ROLE HELPER
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }


    public function isResponsible(): bool
    {
        return $this->role === 'responsible';
    }
}
