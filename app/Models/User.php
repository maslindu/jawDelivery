<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Contracts\LaratrustUser;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Traits\HasRolesAndPermissions;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements LaratrustUser
{
    use HasRolesAndPermissions, HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'fullName',
        'email',
        'password',
        'phone',
        'address',
        'avatar_link'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
    public function getAvatarUrlAttribute()
    {
        return $this->avatar_link
            ? Storage::url('avatar/' . $this->avatar_link)
            : Storage::url('avatar/default-avatar.jpg');
    }
}
