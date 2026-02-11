<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Vendor\VendorReturn;
use App\Http\Controllers\Controller;
use App\Models\Vendor\VendorPurchase;

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




    /**
 * সকল সেলস রিটার্ন লিস্ট নিয়ে আসা
 */
public function returnList()
{
    try {
        // প্রথমে চেক করি রিলেশন ছাড়াই ডাটা আসে কিনা
        $returns = VendorReturn::with(['vendor', 'product'])->orderBy('id', 'desc')->get();
        return response()->json($returns);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

/**
 * সেলস রিটার্ন ডিলিট করা
 */
public function destroyReturn($id)
{
    $return = VendorReturn::find($id);
    if ($return) {
        $return->delete();
        return response()->json(['message' => 'Return record deleted successfully']);
    }
    return response()->json(['message' => 'Record not found'], 404);
}
    
}