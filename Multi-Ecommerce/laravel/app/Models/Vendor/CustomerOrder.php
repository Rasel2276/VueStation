<?php

namespace App\Models\Vendor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class CustomerOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'image',
        'qty',
        'price',
        'customer_name',
        'phone',
        'thana',
        'area',
        'address',
        'vendor_id',
        'payment_method',
        'status'
    ];

    // Vendor-er sathe relationship (Relationship with User table)
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}