<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    use HasFactory;
    public function visit()
    {
        return $this->belongsTo(Visit::class,'visit_id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }
    public function requisitionProducts()
    {
        return $this->hasMany(RequisitionProduct::class,'requisition_id');
    }

    public function StockRequisitionProducts()
    {
        return $this->requisitionProducts()->where('stock_out',false)->count();
    }
    public function stockedOutRequisitionProductsCount()
    {
       return $this->requisitionProducts()->where('stock_out',true)->count();
    }
    public function reviewedBy()
    {
        return $this->belongsTo(User::class,'reviewed_by');
    }
    public function approvedBy()
    {
        return $this->belongsTo(User::class,'approved_by');
    }
    public function quotation()
    {
        return $this->hasOne(CustomerOffer::class,'requisition_id');
    }

    public function warrantyClaim()
    {
        return $this->hasOne(WarrantyClaim::class,'requisition_id');
    }
    public function salesPerson()
    {
       return $this->belongsTo(Customer::class,'customer_id');
    }

}
