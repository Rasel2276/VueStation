<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor\VendorStock; // এই মডেলটি উপরে ইমপোর্ট করুন
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VendorStockController extends Controller
{
    // ১. অ্যাডমিন স্টক দেখার জন্য (আপনার আগের মেথড, নাম পরিবর্তন করতে পারেন)
    public function index()
    {
        $products = DB::table('admin_stock')
            ->join('products', 'admin_stock.product_id', '=', 'products.id')
            ->where('admin_stock.status', 'Available')
            ->select(
                'admin_stock.id',
                'products.product_name',
                'products.product_image', // ইমেজটিও নিয়ে নিন
                'admin_stock.quantity as available_stock',
                'admin_stock.vendor_sale_price as price'
            )
            ->get();

        return response()->json($products, 200);
    }

    // ২. ভেন্ডরের নিজের কেনা স্টক দেখার জন্য (নতুন মেথড)
    public function myStock()
    {
        $vendor_id = Auth::id();

        // ভেন্ডরের নিজস্ব টেবিল থেকে ডাটা আনা
        $myStocks = VendorStock::with(['adminStock.product'])
            ->where('vendor_id', $vendor_id)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($myStocks, 200);
    }



 public function destroyVendorStock($id)
{
    $vendor_id = Auth::id();

    // শুধু নিজের vendor stock খুঁজবে
    $vendorStock = VendorStock::where('vendor_id', $vendor_id)
                    ->where('id', $id)
                    ->first();

    if (!$vendorStock) {
        return response()->json([
            'message' => 'Vendor stock not found'
        ], 404);
    }

    // ✅ শুধু vendor_stock table থেকে delete
    $vendorStock->delete();

    return response()->json([
        'message' => 'Vendor stock deleted successfully'
    ], 200);
}

}