<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Admin\Supplier;
use App\Models\Admin\Product;

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
        'status',
        'purchase_date',
    ];

    /**
     * Admin who made the purchase
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Supplier relation
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Product relation
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

