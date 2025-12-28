<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Admin\Product;
use App\Models\Admin\AdminStock;
use App\Http\Controllers\Controller;

class VendorStockController extends Controller
{
public function index()
{
    $products = Product::select(
            'products.id',
            'products.product_name',
            'products.product_image'
        )
        ->join('admin_stock', 'admin_stock.product_id', '=', 'products.id')
        ->where('admin_stock.status', 'Available')
        ->groupBy('products.id','products.product_name','products.product_image')
        ->selectRaw('SUM(admin_stock.quantity) as available_stock')
        ->selectRaw('MAX(admin_stock.vendor_sale_price) as price')
        ->get();

    return response()->json($products, 200);
}

}

