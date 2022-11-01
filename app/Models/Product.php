<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory;
    public function category()
    {
        return $this->belongsTo(ProductCategory::class,'category_id');
    }
    public function brand()
    {
        return $this->belongsTo(ProductBrand::class,'brand_id');
    }
}
