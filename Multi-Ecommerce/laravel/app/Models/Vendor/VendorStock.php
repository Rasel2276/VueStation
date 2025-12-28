<?php

namespace App\Models\Vendor;

use App\Models\Admin\AdminStock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorStock extends Model
{
    use HasFactory;

    protected $table = 'vendor_stock';

    protected $fillable = [
        'vendor_id',
        'admin_stock_id',
        'quantity',
        'price',
        'status',
    ];

    public $timestamps = false;

    public function adminStock() {
        return $this->belongsTo(AdminStock::class, 'admin_stock_id');
    }
}

