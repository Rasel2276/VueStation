<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use App\Models\Vendor\UserProfile;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password'=>'hashed', // Laravel 10+ auto hash
        ];
    }


    public function profile() {
    return $this->hasOne(UserProfile::class);
}
}