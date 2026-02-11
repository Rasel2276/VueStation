<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor\VendorPurchase;
use Illuminate\Http\Request;

class SalesReportController extends Controller
{
    /**
     * সকল ভেন্ডর পারচেজ (সেলস রিপোর্ট) লিস্ট নিয়ে আসা
     */
    public function index()
    {
        // এখানে 'vendor' (User model) রিলেশন লোড করা হয়েছে
        $sales = VendorPurchase::with(['vendor:id,name,email'])->orderBy('id', 'desc')->get();
        
        return response()->json($sales);
    }

    /**
     * অর্ডারের স্ট্যাটাস আপডেট করা (Pending/Completed/Cancelled)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Completed,Cancelled'
        ]);

        $purchase = VendorPurchase::find($id);

        if (!$purchase) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $purchase->status = $request->status;
        $purchase->save();

        return response()->json([
            'message' => 'Status updated successfully',
            'data' => $purchase
        ]);
    }

    /**
     * রিপোর্ট ডিলিট করা
     */
    public function destroy($id)
    {
        $purchase = VendorPurchase::find($id);
        if ($purchase) {
            $purchase->delete();
            return response()->json(['message' => 'Record deleted successfully']);
        }
        return response()->json(['message' => 'Record not found'], 404);
    }
}