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



    public function getAllProducts()
{
    // সব ভেন্ডরের প্রোডাক্ট একসাথে নিয়ে আসবে (মার্কেটপ্লেসের জন্য)
    $products = CustomerProduct::latest()->get();
    
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

    // ইমেজ হ্যান্ডেল করার লজিক
    $imageName = $request->image; // ডিফল্ট হিসেবে আগের ভ্যালু

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        
        // ইউনিক ফাইল নাম তৈরি করা
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        
        // পাবলিক ফোল্ডার পাথ: public/ui_product_images
        $destinationPath = public_path('ui_product_images');

        // ফোল্ডার না থাকলে তৈরি করবে
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        // ইমেজ মুভ করা
        $image->move($destinationPath, $imageName);
    }

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
        'image'           => $imageName, // এখানে নতুন ফাইল নেমটি যাবে
        'theme_color'     => $request->theme_color ?? '#e4002b',
    ]);

    return response()->json([
        'message' => 'Customer product added successfully',
        'data' => $product
    ]);
}

    public function getVendorStocks()
{
    // ভেন্ডরের স্টক এবং তার সাথে অ্যাডমিন স্টক ও মেইন প্রোডাক্টের ডাটা নিয়ে আসা
    $stocks = \App\Models\Vendor\VendorStock::with(['adminStock.product'])
                ->where('vendor_id', Auth::id())
                ->where('quantity', '>', 0) // শুধু স্টক আছে এমন প্রোডাক্ট
                ->get();

    return response()->json($stocks);
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

    $request->validate([
        'name'     => 'required|string',
        'price'    => 'required|numeric',
        'quantity' => 'required|integer',
    ]);

    $data = $request->all();

    if ($request->hasFile('image')) {
        // নতুন ইমেজ থাকলে আগেরটা ডিলিট করতে পারেন (ঐচ্ছিক)
        if ($product->image && file_exists(public_path('ui_product_images/' . $product->image))) {
            @unlink(public_path('ui_product_images/' . $product->image));
        }

        $image = $request->file('image');
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('ui_product_images'), $imageName);
        $data['image'] = $imageName;
    } else {
        // ইমেজ না পাঠালে আগের ইমেজটিই থাকবে
        unset($data['image']);
    }

    $product->update($data);

    return response()->json([
        'message' => 'Product updated successfully',
        'data' => $product
    ]);
}

    // 🔹 Delete
public function destroy($id)
{
    // প্রোডাক্টটি খুঁজে বের করা
    $product = CustomerProduct::findOrFail($id);

    try {
        // যদি ফোল্ডারে ইমেজ থাকে, তবে সেটি ডিলিট করা
        if ($product->image) {
            $imagePath = public_path('ui_product_images/' . $product->image);
            if (file_exists($imagePath)) {
                @unlink($imagePath); // @ চিহ্ন দেওয়া হয়েছে যাতে ফাইল না থাকলেও এরর না দেয়
            }
        }

        // ডাটাবেস থেকে ডিলিট করা
        $product->delete();

        return response()->json([
            'message' => 'Product and its image deleted successfully'
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Something went wrong while deleting the product',
            'error' => $e->getMessage()
        ], 500);
    }
}
}
