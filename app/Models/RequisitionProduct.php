<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionProduct extends Model
{
    use HasFactory;
    public function visit()
    {
        return $this->belongsTo(Visit::class,'visit_id');
    }
    public function requisition()
    {
        return $this->belongsTo(Requisition::class,'requisition_id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }

}
