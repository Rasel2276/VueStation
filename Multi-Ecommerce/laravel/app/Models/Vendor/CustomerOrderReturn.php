<?php

namespace App\Models\Vendor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerOrderReturn extends Model
{
    use HasFactory;
    protected $fillable = ['customer_order_id', 'order_id', 'user_id', 'vendor_id', 'product_id', 'phone', 'reason', 'status'];
}
