<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnusedRequisitionProduct extends Model
{
    use HasFactory;
    public function visit()
    {
        return $this->belongsTo(Visit::class,'visit_id');
    }
    public function assignedTo()
    {
        return $this->belongsTo(Employee::class,'assign_to');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }
    public function due()
    {
        return $this->quantity - ($this->total_reuse + $this->total_bad);
    }

}
