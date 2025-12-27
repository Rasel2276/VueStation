<?php

namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use App\Models\Admin\AdminStock;
use App\Models\Admin\AdminPurchase;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\SupplierPurchaseReturn;

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
public function supplierReturn(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'supplier_id' => 'required|exists:suppliers,id',
        'quantity' => 'required|integer|min:1',
        'reason' => 'nullable|string'
    ]);

    DB::transaction(function () use ($request) {

        // 🔹 Auto find purchase
        $purchase = AdminPurchase::where('product_id', $request->product_id)
            ->where('supplier_id', $request->supplier_id)
            ->latest()
            ->first();

        if (!$purchase) {
            abort(404, 'Purchase record not found for this product');
        }

        // 🔹 Total stock check
        $totalStock = AdminStock::where('product_id', $request->product_id)
            ->sum('quantity');

        if ($totalStock < $request->quantity) {
            abort(400, 'Return quantity exceeds stock');
        }

        // 🔹 Reduce stock FIFO
        $remainingQty = $request->quantity;

        $stocks = AdminStock::where('product_id', $request->product_id)
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($stocks as $stock) {
            if ($remainingQty <= 0) break;

            if ($stock->quantity >= $remainingQty) {
                $stock->quantity -= $remainingQty;
                $stock->save();
                break;
            } else {
                $remainingQty -= $stock->quantity;
                $stock->quantity = 0;
                $stock->save();
            }
        }

        // 🔹 Save return
        SupplierPurchaseReturn::create([
            'admin_purchase_id' => $purchase->id,
            'admin_id' => Auth::id(),
            'supplier_id' => $request->supplier_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'reason' => $request->reason,
            'status' => 'Completed',
            'return_date' => now()
        ]);
    });

    return response()->json([
        'success' => true,
        'message' => 'Product returned to supplier successfully'
    ]);
}

        // 🔹 Supplier Return History
    public function returnHistory()
    {
        $returns = SupplierPurchaseReturn::with(['product','supplier'])
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($returns);
    }

}
