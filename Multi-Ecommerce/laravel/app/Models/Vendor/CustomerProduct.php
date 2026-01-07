<?php

namespace App\Models\Vendor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerProduct extends Model
{
    use HasFactory;

    protected $table = 'customer_products';

    protected $fillable = [
        'vendor_stock_id',
        'product_id',
        'vendor_id',
        'name',
        'brand',
        'category',
        'price',
        'old_price',
        'quantity',
        'details',
        'image',
        'theme_color',
    ];

    // 🔗 Relationships
    public function vendor()
    {
        return $this->belongsTo(\App\Models\User::class, 'vendor_id');
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id');
    }

    public function vendorStock()
    {
        return $this->belongsTo(\App\Models\Vendor\VendorStock::class, 'vendor_stock_id');
    }
}
