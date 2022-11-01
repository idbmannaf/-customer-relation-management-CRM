<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challan extends Model
{
    use HasFactory;
    public function items()
    {
        return $this->hasMany(ChallanItem::class,'challan_id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }
    public function visit()
    {
        return $this->belongsTo(Visit::class,'visit_id');
    }

}
