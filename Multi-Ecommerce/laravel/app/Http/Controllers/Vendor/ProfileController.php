<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor\UserProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('profile');
        return response()->json($user);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // ১. ইউজার টেবিলের নাম আপডেট
        if ($request->full_name) {
            $user->name = $request->full_name;
        }

        // ২. পাসওয়ার্ড আপডেট
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        // ৩. প্রোফাইল ডাটা রেডি
        $profileData = [
            'full_name'           => $request->full_name,
            'phone'               => $request->phone,
            'thana_upazila'       => $request->thana_upazila,
            'area_village'        => $request->area_village,
            'home_road_apartment' => $request->home_road_apartment,
        ];

        if ($user->role === 'vendor') {
            $profileData['shop_name'] = $request->shop_name;
        }

        // ৪. ইমেজ আপলোড
        if ($request->hasFile('profile_picture')) {
            // পুরাতন ছবি ডিলিট
            if ($user->profile && $user->profile->profile_picture) {
                $oldPath = public_path($user->profile->profile_picture);
                if (File::exists($oldPath)) { File::delete($oldPath); }
            }

            $image = $request->file('profile_picture');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/profile');

            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }

            $image->move($destinationPath, $name);
            $profileData['profile_picture'] = 'uploads/profile/' . $name;
        }

        // ৫. প্রোফাইল টেবিল আপডেট
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        return response()->json([
            'status' => 'success',
            'message' => 'সবকিছু সফলভাবে আপডেট হয়েছে!'
        ]);
    }
}