<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallToSendProductRequest extends Model
{
    use HasFactory;
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }
    public function call()
    {
        return $this->belongsTo(Call::class,'call_id');
    }
}
