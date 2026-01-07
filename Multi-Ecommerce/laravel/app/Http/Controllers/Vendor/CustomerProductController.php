<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor\CustomerProduct;
use Illuminate\Support\Facades\Auth;

class CustomerProductController extends Controller
{
    // 🔹 List
    public function index()
    {
        $products = CustomerProduct::where('vendor_id', Auth::id())
                    ->latest()
                    ->get();

        return response()->json($products);
    }

    // 🔹 Store
    public function store(Request $request)
    {
        $request->validate([
            'vendor_stock_id' => 'required',
            'product_id'      => 'required',
            'name'            => 'required|string',
            'price'           => 'required|numeric',
            'quantity'        => 'required|integer',
        ]);

        $product = CustomerProduct::create([
            'vendor_stock_id' => $request->vendor_stock_id,
            'product_id'      => $request->product_id,
            'vendor_id'       => Auth::id(),
            'name'            => $request->name,
            'brand'           => $request->brand,
            'category'        => $request->category,
            'price'           => $request->price,
            'old_price'       => $request->old_price,
            'quantity'        => $request->quantity,
            'details'         => $request->details,
            'image'           => $request->image,
            'theme_color'     => $request->theme_color ?? '#e4002b',
        ]);

        return response()->json([
            'message' => 'Customer product added successfully',
            'data' => $product
        ]);
    }

    // 🔹 Show
    public function show($id)
    {
        return CustomerProduct::findOrFail($id);
    }

    // 🔹 Update
    public function update(Request $request, $id)
    {
        $product = CustomerProduct::findOrFail($id);

        $product->update($request->all());

        return response()->json([
            'message' => 'Customer product updated successfully'
        ]);
    }

    // 🔹 Delete
    public function destroy($id)
    {
        CustomerProduct::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Customer product deleted successfully'
        ]);
    }
}
