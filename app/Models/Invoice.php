<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = ['advance','due','payment_status'];
    public function items()
    {
        return $this->hasMany(InvoiceItem::class,'invoice_id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }
    public function preparedBy()
    {
        return $this->belongsTo(User::class,'prepared_by');
    }
    public function approvedBy()
    {
        return $this->belongsTo(User::class,'approved_by');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }

    //For Collection
    public function assignedTo()
    {
        return $this->belongsTo(Employee::class,'assigned_to');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class,'assigned_by');
    }
    public function visit_plan()
    {
        return $this->hasMany(VisitPlan::class,'invoice_id');
    }
    public function hasVisitPlan()
    {
        return (bool) $this->visit_plan()->count();
    }

    public function transections()
    {
        return $this->hasMany(CollectionHistory::class,'invoice_id');
    }
    public function hasTransectionHistory()
    {
        return (bool) $this->transections()->count();
    }


}
