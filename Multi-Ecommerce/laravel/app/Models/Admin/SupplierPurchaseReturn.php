<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class SupplierPurchaseReturn extends Model
{
    protected $table = 'supplier_purchase_returns';

    protected $fillable = [
        'admin_purchase_id',
        'admin_id',
        'supplier_id',
        'product_id',
        'quantity',
        'reason',
        'status',
        'return_date'
    ];
}

