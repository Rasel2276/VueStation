<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor\VendorPurchase;
use App\Models\Vendor\VendorStock;
use App\Models\Admin\AdminStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VendorPurchaseController extends Controller
{
    public function store(Request $request)
    {
        $vendor_id = Auth::id();
        $items = $request->all();

        DB::beginTransaction();

        try {
            foreach ($items as $item) {

                $admin_stock_id = (int) $item['admin_stock_id'];
                $quantity       = (int) $item['quantity'];
                $unit_price     = (float) $item['price'];

                // Admin stock check
                $adminStock = AdminStock::find($admin_stock_id);

                if (!$adminStock || $adminStock->quantity < $quantity) {
                    throw new \Exception('Admin stock insufficient');
                }

                // Admin stock minus
                $adminStock->quantity -= $quantity;
                $adminStock->save();

                // Purchase history (ALWAYS new record)
                VendorPurchase::create([
                    'vendor_id'      => $vendor_id,
                    'admin_stock_id' => $admin_stock_id,
                    'quantity'       => $quantity,
                    'price'          => $unit_price,
                    'status'         => 'Completed'
                ]);

                // Vendor stock
                $vendorStock = VendorStock::where('vendor_id', $vendor_id)
                    ->where('admin_stock_id', $admin_stock_id)
                    ->first();

                $totalPrice = $unit_price * $quantity;

                if ($vendorStock) {

                    // ✅ quantity যোগ
                    $vendorStock->quantity = $vendorStock->quantity + $quantity;

                    // ✅ price যোগ (TOTAL AMOUNT)
                    $vendorStock->price = $vendorStock->price + $totalPrice;

                    $vendorStock->save();

                } else {

                    VendorStock::create([
                        'vendor_id'      => $vendor_id,
                        'admin_stock_id' => $admin_stock_id,
                        'quantity'       => $quantity,
                        'price'          => $totalPrice,
                        'status'         => 'Available'
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'message' => 'Purchase successful & vendor stock updated'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $vendor_id = Auth::id();

        $purchases = VendorPurchase::with(['adminStock.product'])
            ->where('vendor_id', $vendor_id)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($purchases, 200);
    }


 public function destroy($id)
   {
    $vendor_id = Auth::id();

    $purchase = VendorPurchase::where('vendor_id', $vendor_id)
                ->where('id', $id)
                ->first();

    if (!$purchase) {
        return response()->json([
            'message' => 'Purchase record not found'
        ], 404);
    }

    $purchase->delete();

    return response()->json([
        'message' => 'Purchase record deleted successfully'
    ], 200);
   }

}
