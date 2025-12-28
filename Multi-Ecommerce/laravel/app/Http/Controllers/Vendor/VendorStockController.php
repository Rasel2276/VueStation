<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Admin\Product;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class VendorStockController extends Controller
{
    public function index()
    {
        // সরাসরি admin_stock থেকে আইডি নিচ্ছি যেন Foreign Key এরর না দেয়
        $products = DB::table('admin_stock')
            ->join('products', 'admin_stock.product_id', '=', 'products.id')
            ->where('admin_stock.status', 'Available')
            ->select(
                'admin_stock.id', // এটিই হবে আমাদের admin_stock_id
                'products.product_name',
                'admin_stock.quantity as available_stock',
                'admin_stock.vendor_sale_price as price'
            )
            ->get();

        return response()->json($products, 200);
    }
}