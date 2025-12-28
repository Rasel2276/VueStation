<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor\VendorReturn;
use App\Models\Vendor\VendorStock;
use App\Models\Admin\AdminStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VendorReturnController extends Controller
{
    // ১. রিটার্ন স্টোর করা এবং স্টক আপডেট করা
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer', // এটি মূলত admin_stock_id হিসেবে আসছে
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string',
        ]);

        $vendor_id = Auth::id();
        $admin_stock_id = $request->product_id;
        $return_qty = (int) $request->quantity;

        DB::beginTransaction();
        try {
            // ১. ভেন্ডরের স্টক থেকে ডাটা চেক করুন
            $vStock = VendorStock::where('vendor_id', $vendor_id)
                                ->where('admin_stock_id', $admin_stock_id)
                                ->first();

            if (!$vStock || $vStock->quantity < $return_qty) {
                throw new \Exception("আপনার কাছে পর্যাপ্ত স্টক নেই এই পরিমাণ রিটার্ন করার জন্য।");
            }

            // ২. এডমিন স্টক খুঁজে বের করুন
            $aStock = AdminStock::find($admin_stock_id);
            if (!$aStock) {
                throw new \Exception("এডমিন মেইন স্টক পাওয়া যায়নি।");
            }

            // ৩. লজিক: ভেন্ডর স্টক কমবে (-) এবং এডমিন স্টক বাড়বে (+)
            $vStock->decrement('quantity', $return_qty);
            $aStock->increment('quantity', $return_qty);

            // ৪. রিটার্ন টেবিলে ডাটা সেভ
            $vendorReturn = VendorReturn::create([
                'vendor_id'   => $vendor_id,
                'admin_id'    => $aStock->admin_id ?? 1, // এডমিন আইডি
                'product_id'  => $aStock->product_id,   // অরিজিনাল প্রোডাক্ট আইডি
                'quantity'    => $return_qty,
                'reason'      => $request->reason,
                'status'      => 'Completed' 
            ]);

            DB::commit();
            return response()->json([
                'message' => 'রিটার্ন সফল হয়েছে এবং স্টক আপডেট করা হয়েছে।',
                'data' => $vendorReturn
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    // ২. রিটার্ন লিস্ট দেখা
    public function index()
    {
        $vendor_id = Auth::id();

        $returns = VendorReturn::with(['product'])
            ->where('vendor_id', $vendor_id)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($returns, 200);
    }

    // ৩. ডিলিট করা
    public function destroy($id)
    {
        $vendor_id = Auth::id();
        $return = VendorReturn::where('vendor_id', $vendor_id)->find($id);

        if (!$return) {
            return response()->json(['message' => 'Return not found'], 404);
        }

        $return->delete();
        return response()->json(['message' => 'Return deleted successfully'], 200);
    }
}