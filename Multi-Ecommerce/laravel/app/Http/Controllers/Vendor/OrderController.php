<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor\CustomerOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * 1. Vendor Orders List
     * Ei function-ti vendor-er dashboard-e tar nizer order gulo dekhabe.
     */
    public function index()
    {
        // Shudhu logged-in vendor-er order gulo niye ashbe
        $orders = CustomerOrder::where('vendor_id', Auth::id())
                    ->latest()
                    ->get();

        return response()->json($orders);
    }

    /**
     * 2. Store Order (From Checkout)
     * Customer jokhon order place korbe, tokhon eita kaj korbe.
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

        // Ekta common Order ID generate kora (Ex: ORD-AB123)
        $commonOrderId = 'ORD-' . strtoupper(Str::random(5));

        // Cart-e thaka protiti product-er jonno alada entry hobe
        foreach ($request->cartItems as $item) {
            CustomerOrder::create([
                'order_id'      => $commonOrderId,
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
                'vendor_id'     => $item['vendor_id'], // Ei product-ta jar tar ID
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
     * Vendor tar dashboard theke status (Confirmed, Shipped etc) change korle.
     */
    public function updateStatus(Request $request, $id)
    {
        $order = CustomerOrder::where('vendor_id', Auth::id())->findOrFail($id);
        
        $request->validate([
            'status' => 'required|string'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'Order status updated to ' . $request->status,
            'data' => $order
        ]);
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
}