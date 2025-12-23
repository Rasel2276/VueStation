<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class AdminPurchase extends Model
{
    protected $fillable = [
        'admin_id',
        'supplier_id',
        'product_id',
        'quantity',
        'purchase_price',
        'vendor_sale_price',
        'total',
        'status'
    ];
}
