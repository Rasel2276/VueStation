<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Product;
use App\Models\Admin\Supplier;
use App\Models\User;

class SupplierPurchaseReturn extends Model
{
    use HasFactory;

    protected $table = 'supplier_purchase_returns';

    protected $fillable = [
        'admin_purchase_id',
        'admin_id',
        'supplier_id',
        'product_id',
        'quantity',
        'reason',
        'status',
        'return_date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
