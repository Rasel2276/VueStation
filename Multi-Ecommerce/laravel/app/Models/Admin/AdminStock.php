<?php

namespace App\Models\Admin;

use App\Models\Admin\Product;
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

    /**
     * Product relation
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}

