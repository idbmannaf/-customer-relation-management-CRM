<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProduct extends Model
{
    use HasFactory;
    protected $fillable = ['visit_id'];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
