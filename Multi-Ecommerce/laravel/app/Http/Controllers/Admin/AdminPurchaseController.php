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


        // PURCHASE DELETE
    public function deletePurchase($id)
    {
        $purchase = AdminPurchase::find($id);
        if (!$purchase) {
            return response()->json([
                'success' => false,
                'message' => 'Purchase not found'
            ], 404);
        }

        $purchase->delete();

        return response()->json([
            'success' => true,
            'message' => 'Purchase deleted successfully'
        ]);
    }

    public function index()
    {
        $purchases = AdminPurchase::with(['supplier','product','admin'])
            ->latest()
            ->get();

        return response()->json($purchases, 200);
    }

        // STOCK SHOW (NEW FUNCTION)
    public function stockIndex()
    {
        $stocks = AdminStock::with('product')
            ->latest()
            ->get();

        return response()->json($stocks, 200);
    }

     // STOCK DELETE
    public function deleteStock($id)
    {
        $stock = AdminStock::find($id);
        if (!$stock) {
            return response()->json([
                'success' => false,
                'message' => 'Stock not found'
            ], 404);
        }

        $stock->delete();

        return response()->json([
            'success' => true,
            'message' => 'Stock deleted successfully'
        ]);
    }


    // SUPPLIER RETURN (NEW FUNCTION)
public function returnStock(Request $request, $productId)
{
    $request->validate([
        'quantity' => 'required|integer|min:1'
    ]);

    $stock = AdminStock::where('product_id', $productId)->first();

    if (!$stock) {
        return response()->json([
            'success' => false,
            'message' => 'Stock not found for this product'
        ], 404);
    }

    if ($stock->quantity < $request->quantity) {
        return response()->json([
            'success' => false,
            'message' => 'Return quantity exceeds available stock'
        ], 400);
    }

    // Reduce stock quantity
    $stock->quantity -= $request->quantity;
    $stock->save();

    return response()->json([
        'success' => true,
        'message' => "Stock returned successfully. Remaining stock: {$stock->quantity}",
        'stock' => $stock
    ]);
}

}
