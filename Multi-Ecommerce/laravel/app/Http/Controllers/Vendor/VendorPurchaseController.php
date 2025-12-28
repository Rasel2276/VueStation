<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor\VendorPurchase;
use App\Models\Vendor\VendorStock;

class VendorPurchaseController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();
        $vendor_id = $request->user()->id;

        foreach ($data as $item) {
            $admin_stock_id = (int) $item['admin_stock_id'];
            $quantity = (int) $item['quantity'];
            $price = (float) $item['price'];

            VendorPurchase::create([
                'vendor_id' => $vendor_id,
                'admin_stock_id' => $admin_stock_id,
                'quantity' => $quantity,
                'price' => $price
            ]);

            VendorStock::create([
                'vendor_id' => $vendor_id,
                'admin_stock_id' => $admin_stock_id,
                'quantity' => $quantity,
                'price' => $price,
                'status' => 'Available'
            ]);
        }

        return response()->json(['message' => 'Purchase successful'], 200);
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
