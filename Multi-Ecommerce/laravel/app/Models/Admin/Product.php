<?php

namespace App\Models\Admin;

use App\Models\Admin\Category;
use App\Models\Admin\Supplier;
use App\Models\Admin\AdminStock;
use App\Models\Admin\Subcategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'sku',
        'category_id',
        'subcategory_id',
        'supplier_id',
        'base_price',
        'description',
        'product_image',
        'color',
        'size',
        'featured',
        'status',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function adminStocks()
    {
        return $this->hasMany(AdminStock::class, 'product_id');
    }
}

