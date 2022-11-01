<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarrantyClaim extends Model
{
    use HasFactory;
    public function requisition()
    {
        return $this->belongsTo(Requisition::class, 'requisition_id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function visit_plan()
    {
        return $this->belongsTo(VisitPlan::class, 'visit_plan_id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function preparedBy()
    {
        return $this->belongsTo(User::class,'prepared_by');
    }
    public function managerCordinetor()
    {
        return $this->belongsTo(User::class,'manager');
    }
    public function accountDepartment()
    {
        return $this->belongsTo(User::class,'account_department');
    }
    public function oparationManager()
    {
        return $this->belongsTo(User::class,'oparation_manager');
    }
}
