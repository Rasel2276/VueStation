<?php

namespace App\Models\Vendor;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id', 'full_name', 'phone', 'shop_name', 
        'thana_upazila', 'area_village', 'home_road_apartment', 'profile_picture'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

