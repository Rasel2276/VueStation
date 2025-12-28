<?php

namespace App\Models\Vendor;

use App\Models\Admin\AdminStock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorPurchase extends Model
{
    use HasFactory;

    protected $table = 'vendor_purchases';

    protected $fillable = [
        'vendor_id',
        'admin_stock_id',
        'quantity',
        'price',
    ];

    public $timestamps = false;

    public function adminStock() {
        return $this->belongsTo(AdminStock::class, 'admin_stock_id');
    }
}

