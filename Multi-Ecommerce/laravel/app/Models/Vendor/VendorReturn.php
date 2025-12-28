<?php

namespace App\Models\Vendor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorReturn extends Model
{
    use HasFactory;

    protected $table = 'vendor_returns';

    protected $fillable = [
        'vendor_id', 'admin_id', 'product_id', 'quantity', 'reason', 'status', 'return_date'
    ];

    public function product()
    {
        return $this->belongsTo(\App\Models\Admin\Product::class, 'product_id');
    }

    public function admin()
    {
        return $this->belongsTo(\App\Models\User::class, 'admin_id');
    }
}

