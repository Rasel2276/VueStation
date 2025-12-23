<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\AdminPurchase;
use App\Models\Admin\AdminStock;
use DB;

class AdminPurchaseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required',
            'purchases' => 'required|array'
        ]);

        DB::transaction(function () use ($request) {

            foreach ($request->purchases as $item) {

                $total = $item['quantity'] * $item['purchase_price'];

                // Insert purchase
                AdminPurchase::create([
                    'admin_id' => Auth::id(),
                    'supplier_id' => $item['supplier_id'],
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'purchase_price' => $item['purchase_price'],
                    'vendor_sale_price' => $item['vendor_sale_price'],
                    'total' => $total,
                    'status' => 'Completed'
                ]);

                // Auto stock insert
                AdminStock::create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'purchase_price' => $item['purchase_price'],
                    'vendor_sale_price' => $item['vendor_sale_price'],
                    'status' => 'Available'
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Purchase & Stock added successfully'
        ]);
    }
}
