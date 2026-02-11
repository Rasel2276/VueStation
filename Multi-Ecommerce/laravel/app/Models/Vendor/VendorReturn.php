<?php

namespace App\Models\Vendor;

use App\Models\User;
use App\Models\Admin\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorReturn extends Model
{
    use HasFactory;

    protected $table = 'vendor_returns';

    protected $fillable = [
        'vendor_id', 'admin_id', 'product_id', 'quantity', 'reason', 'status', 'return_date'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}

