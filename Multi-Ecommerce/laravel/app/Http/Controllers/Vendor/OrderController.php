<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor\CustomerOrder;
use App\Models\Vendor\VendorStock;
use App\Models\Admin\AdminStock; // এডমিন স্টক মডেল যুক্ত করা হয়েছে
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * 1. Vendor Orders List
     * ভেন্ডর ড্যাশবোর্ডে শুধুমাত্র লগইন করা ভেন্ডরের অর্ডারগুলো দেখাবে।
     */
    public function index()
    {
        $orders = CustomerOrder::where('vendor_id', Auth::id())
                    ->latest()
                    ->get();

        return response()->json($orders);
    }

    /**
     * 2. Store Order (From Checkout)
     * কাস্টমার যখন চেকআউট করবে তখন এই ফাংশনটি কল হবে।
     */
public function storeOrder(Request $request)
{
    $request->validate([
        'customer_name' => 'required|string',
        'phone'         => 'required|string',
        'thana'         => 'required',
        'area'          => 'required',
        'address'       => 'required',
        'cartItems'     => 'required|array',
    ]);

    $commonOrderId = 'ORD-' . strtoupper(Str::random(5));

    foreach ($request->cartItems as $item) {
        CustomerOrder::create([
            'order_id'      => $commonOrderId,
            
            // ✅ পরিবর্তন এখানে: এটি লগইন করা থাকলে আইডি দিবে, না থাকলে NULL দিবে।
            'user_id'       => auth('sanctum')->id(), 
            
            'product_id'    => $item['id'], 
            'product_name'  => $item['name'],
            'image'         => $item['image'] ?? null,
            'qty'           => $item['qty'],
            'price'         => $item['price'] * $item['qty'],
            'customer_name' => $request->customer_name,
            'phone'         => $request->phone,
            'thana'         => $request->thana,
            'area'          => $request->area,
            'address'       => $request->address,
            'vendor_id'     => $item['vendor_id'],
            'payment_method'=> $request->payment_method ?? 'Cash On Delivery',
            'status'        => 'Pending',
        ]);
    }

    return response()->json([
        'message' => 'Order placed successfully!',
        'order_id' => $commonOrderId
    ], 201);
}

    /**
     * 3. Update Status
     * ভেন্ডর যখন স্ট্যাটাস আপডেট করবে তখন স্টক অটোমেটিক ম্যানেজ হবে।
     */
public function updateStatus(Request $request, $id)
{
    $request->validate(['status' => 'required|string']);

    $vendor_id = Auth::id();
    $order = CustomerOrder::where('vendor_id', $vendor_id)->findOrFail($id);
    
    $newStatus = $request->status;
    $oldStatus = $order->status;

    DB::beginTransaction();

    try {
        // ১. কনফার্ম করলে স্টকের লজিক
        if ($newStatus == 'Confirmed' && $oldStatus != 'Confirmed') {
            $productInfo = DB::table('customer_products')
                            ->where('id', $order->product_id)
                            ->first();

            if (!$productInfo) {
                throw new \Exception('Product mapping not found in customer products!');
            }

            $vendorStock = VendorStock::where('vendor_id', $vendor_id)
                            ->where('id', $productInfo->vendor_stock_id) 
                            ->first();

            if (!$vendorStock) {
                throw new \Exception('Stock record not found in your inventory!');
            }

            if ($vendorStock->quantity < $order->qty) {
                throw new \Exception('Insufficient stock! Available: ' . $vendorStock->quantity);
            }

            $vendorStock->decrement('quantity', $order->qty);
            
            // কনফার্ম এর জন্য কাস্টম মেসেজ
            $responseMessage = "Order confirmed and stock updated successfully!";
        } 
        else {
            // অন্য সব স্ট্যাটাসের জন্য ডাইনামিক মেসেজ (Shipped, Delivered, etc.)
            $responseMessage = "Order status updated to " . $newStatus . " successfully!";
        }

        // ২. কনফার্ম থেকে ক্যানসেল করলে স্টক ফেরত দেওয়া
        if ($newStatus == 'Cancelled' && $oldStatus == 'Confirmed') {
            $productInfo = DB::table('customer_products')->where('id', $order->product_id)->first();
            if ($productInfo) {
                $vendorStock = VendorStock::where('vendor_id', $vendor_id)
                                ->where('id', $productInfo->vendor_stock_id)
                                ->first();
                if ($vendorStock) {
                    $vendorStock->increment('quantity', $order->qty);
                }
            }
            $responseMessage = "Order cancelled and stock returned successfully!";
        }

        // ৩. ফাইনাল আপডেট
        $order->update(['status' => $newStatus]);
        DB::commit();

        return response()->json(['message' => $responseMessage]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['message' => 'Error: ' . $e->getMessage()], 400);
    }
}
    /**
     * 4. Delete Order
     */
    public function destroy($id)
    {
        $order = CustomerOrder::where('vendor_id', Auth::id())->findOrFail($id);
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }


    /**
 * 5. Track Order for Customer
 * কাস্টমার যখন Order ID এবং Phone দিয়ে সার্চ করবে।
 */
public function trackOrder(Request $request)
{
    $request->validate([
        'order_id' => 'required|string',
        'phone'    => 'required|string',
    ]);

    // আমরা 'latest()' ব্যবহার করছি কারণ একই আইডিতে মাল্টিপল প্রোডাক্ট থাকতে পারে
    $order = CustomerOrder::where('order_id', $request->order_id)
                ->where('phone', $request->phone)
                ->first(); // প্রথমটি নেওয়া হচ্ছে হেডার ইনফোর জন্য

    if ($order) {
        return response()->json([
            'success' => true,
            'order'   => $order
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'No order found! Please check your Order ID and Phone Number and try again.'
    ], 404);
}


public function customerOrders(Request $request)
{
    // ১. লগইন করা কাস্টমারের আইডি নেওয়া
    $userId = auth('sanctum')->id();

    // ২. ইউজার আইডি দিয়ে অর্ডারগুলো খুঁজে বের করা
    // এটি অনেক বেশি নির্ভুল (কারণ একই ফোন নাম্বার অন্য কেউ ব্যবহার করতে পারে, কিন্তু আইডি ইউনিক)
    $orders = CustomerOrder::where('user_id', $userId)
                ->orderBy('id', 'desc')
                ->get();

    return response()->json($orders);
}

public function cancelOrder($id)
{
    $order = CustomerOrder::where('id', $id)
                ->where('user_id', auth('sanctum')->id()) // নিজের অর্ডার কি না চেক
                ->first();

    if (!$order) {
        return response()->json(['message' => 'Order not found'], 404);
    }

    $order->update(['status' => 'Cancelled']);

    return response()->json(['message' => 'Order cancelled successfully!']);
}

}