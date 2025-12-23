<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class AdminStock extends Model
{
    protected $table = 'admin_stock';

    protected $fillable = [
        'product_id',
        'quantity',
        'purchase_price',
        'vendor_sale_price',
        'status'
    ];
}
