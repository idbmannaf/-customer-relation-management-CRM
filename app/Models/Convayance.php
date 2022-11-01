<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Convayance extends Model
{
    use HasFactory;
    public function items()
    {
        return $this->hasMany(ConvayanceItems::class,'convayance_id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }
    public function visit()
    {
        return $this->belongsTo(Visit::class,'visit_id');
    }
    public function visit_plan()
    {
        return $this->belongsTo(VisitPlan::class,'visit_plan_id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }
}
