<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitPlan extends Model
{
    use HasFactory;
    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }
    public function visits()
    {
        return $this->hasMany(Visit::class,'visit_plan_id');
    }
    public function office()
    {
        return $this->belongsTo(OfficeLocation::class,'customer_office_location_id');
    }
    public function call()
    {
        return $this->belongsTo(Call::class,'call_id');
    }
    public function sales_items()
    {
        return $this->hasMany(SalesItems::class,'visit_plan_id');
    }
    public function total_sales_price()
    {
        return $this->sales_items()->sum('product_final_price');
    }
    public function serviceProducts()
    {
        return $this->hasMany(ServiceProduct::class,'visit_plan_id');
    }
    public function service_requirment_batt_spear_parts()
    {
        return $this->hasMany(ServiceRequirementsofbatteryAndSpare::class,'visit_plan_id');
    }
    public function hasRequirementBatteryAndSpearpart()
    {
        return (bool) $this->service_requirment_batt_spear_parts()->count();
    }
    public function files()
    {
        return $this->hasMany(GlobalImage::class,'visit_plan_id');
    }
    public function invoice()
    {
        return $this->belongsTo(Invoice::class,'invoice_id');
    }
}
