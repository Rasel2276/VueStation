<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor\VendorPurchase;
use App\Models\Vendor\VendorStock;
use Illuminate\Support\Facades\DB;

class VendorPurchaseController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();
        $vendor_id = $request->user()->id;

        DB::beginTransaction(); // সেফটির জন্য ট্রানজ্যাকশন শুরু
        try {
            foreach ($data as $item) {
                // ১. পারচেজ রেকর্ড তৈরি
                VendorPurchase::create([
                    'vendor_id'      => $vendor_id,
                    'admin_stock_id' => (int) $item['admin_stock_id'],
                    'quantity'       => (int) $item['quantity'],
                    'price'          => (float) $item['price'],
                    'status'         => 'Completed' // স্ট্যাটাস সরাসরি সেট করে দিলাম
                ]);

                // ২. ভেন্ডর স্টকে প্রোডাক্ট যোগ করা
                VendorStock::create([
                    'vendor_id'      => $vendor_id,
                    'admin_stock_id' => (int) $item['admin_stock_id'],
                    'quantity'       => (int) $item['quantity'],
                    'price'          => (float) $item['price'],
                    'status'         => 'Available'
                ]);
            }
            DB::commit();
            return response()->json(['message' => 'Purchase successful!'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function index(Request $request)
    {
        $vendor_id = $request->user()->id;
        $purchases = VendorPurchase::with('adminStock')
            ->where('vendor_id', $vendor_id)
            ->get();
        return response()->json($purchases);
    }
}