<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor\CustomerOrderReturn;
use App\Models\Vendor\CustomerOrder;
use App\Models\Vendor\VendorStock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerOrderReturnController extends Controller 
{
    
    public function storeReturnRequest(Request $request) {
        $order = CustomerOrder::where('order_id', $request->order_id)
                    ->where('phone', $request->phone)
                    ->where('user_id', auth('sanctum')->id())
                    ->first();

        if (!$order || $order->status !== 'Delivered') {
            return response()->json(['message' => 'রিটার্ন করা সম্ভব নয়!'], 400);
        }

        CustomerOrderReturn::create([
            'customer_order_id' => $order->id,
            'order_id' => $order->order_id,
            'user_id' => auth('sanctum')->id(),
            'vendor_id' => $order->vendor_id,
            'product_id' => $order->product_id,
            'phone' => $request->phone,
            'reason' => $request->reason,
        ]);

        $order->update(['status' => 'Return Requested']);
        return response()->json(['message' => 'রিটার্ন রিকোয়েস্ট পাঠানো হয়েছে!']);
    }

    
   
        public function vendorReturnList() {
                $returns = CustomerOrderReturn::where('vendor_id', Auth::id())
                         ->latest()
                         ->get();
                return response()->json($returns);
       }
    
    
    
    
    
    public function updateReturnStatus(Request $request, $id) {
        $returnReq = CustomerOrderReturn::where('vendor_id', Auth::id())->findOrFail($id);
        $order = CustomerOrder::findOrFail($returnReq->customer_order_id);

        DB::transaction(function () use ($request, $returnReq, $order) {
            if ($request->status == 'Approved') {
                $product = DB::table('customer_products')->where('id', $order->product_id)->first();
                if ($product) {
                    VendorStock::where('id', $product->vendor_stock_id)->increment('quantity', $order->qty);
                }
                $order->update(['status' => 'Returned']);
            }
            $returnReq->update(['status' => $request->status]);
        });

        return response()->json(['message' => 'স্ট্যাটাস আপডেট সম্পন্ন!']);
    }


    
          public function myReturns() {
               return CustomerOrderReturn::where('user_id', auth()->id())->latest()->get();
    }




          public function cancelReturn($id) {
                  $return = CustomerOrderReturn::where('user_id', auth()->id())
                          ->where('status', 'Pending')
                          ->findOrFail($id);
    
    
                  CustomerOrder::where('id', $return->customer_order_id)->update(['status' => 'Delivered']);
    
                  $return->delete();
                  return response()->json(['message' => 'রিটার্ন রিকোয়েস্ট ক্যান্সেল করা হয়েছে!']);
    }
}
