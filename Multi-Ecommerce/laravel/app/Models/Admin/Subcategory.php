<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Category;

class Subcategory extends Model
{
    use HasFactory;

    protected $table = 'subcategories';

    protected $fillable = [
        'parent_category_id',
        'subcategory_name',
        'slug',
        'description',
        'subcategory_image',
        'status',
    ];


    public function category()
    {
        return $this->belongsTo(Category::class, 'parent_category_id');
    }
}

