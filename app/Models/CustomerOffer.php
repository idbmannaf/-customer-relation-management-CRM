<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerOffer extends Model
{
    use HasFactory;
    public function items()
    {
        return $this->hasMany(CustomerOfferItem::class,'customer_offer_id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }
    public function total_unit_price()
    {
        return $this->items()->sum('unit_price');
    }
    public function total_quantity()
    {
        return $this->items()->sum('quantity');
    }
    public function total_price()
    {
        return $this->items()->sum('total_price');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }
    public function addedBy()
    {
        return $this->belongsTo(User::class,'addedBy_id');
    }
    public function fiels()
    {
        return $this->hasMany(GlobalImage::class,'offer_id');
    }
    public function invoice()
    {
        return $this->hasMany(Invoice::class,'offer_id');
    }
    public function visit()
    {
        return $this->belongsTo(Visit::class,'visit_id');
    }

}
