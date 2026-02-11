<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * শুধুমাত্র ভেন্ডরদের লিস্ট পাওয়ার জন্য
     */
    public function index()
    {
        try {
            // যাদের রোল 'vendor' শুধুমাত্র তাদের ডাটা আনা হচ্ছে
            $vendors = User::where('role', 'vendor')
                ->select('id', 'name', 'email', 'role', 'created_at')
                ->orderBy('id', 'desc')
                ->get();

            return response()->json($vendors, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vendors not found'
            ], 500);
        }
    }


    public function customers()
    {
        try {
            // যাদের রোল 'customer' শুধুমাত্র তাদের ডাটা আনা হচ্ছে
            $customers = User::where('role', 'customer')
                ->select('id', 'name', 'email', 'role', 'created_at')
                ->orderBy('id', 'desc')
                ->get();

            return response()->json($customers, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Customers not found'
            ], 500);
        }
    }

    /**
     * ভেন্ডর ডিলিট করার অপশন (যদি প্রয়োজন হয়)
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['message' => 'Vendor removed successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete'], 500);
        }
    }
}
