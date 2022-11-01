<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBrand extends Model
{
    use HasFactory;
    public function products()
    {
        return $this->hasMany(Product::class,'brand_id');
    }
    public function hasProducts()
    {
        return (bool) $this->products()->count();
    }
}
