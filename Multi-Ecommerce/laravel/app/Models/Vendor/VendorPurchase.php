<?php

namespace App\Models\Vendor;

use App\Models\User;
use App\Models\Admin\AdminStock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorPurchase extends Model
{
    use HasFactory;

    protected $table = 'vendor_purchases';
    public $timestamps = false; // আপনার মাইগ্রেশনে timestamps() নেই

    protected $fillable = [
        'vendor_id',
        'admin_stock_id',
        'quantity',
        'price',
        'status', // এটা অ্যাড করলাম
    ];

    public function adminStock() {
        return $this->belongsTo(AdminStock::class, 'admin_stock_id');
    }

    public function vendor()
{
    return $this->belongsTo(User::class, 'vendor_id');
}


}