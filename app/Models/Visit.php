<?php

namespace App\Models;

use App\Models\Scopes\VisitScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::addGlobalScope(new VisitScope);
    // }
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
    public function sales_items()
    {
        return $this->hasMany(SalesItems::class, 'visit_id');
    }
    public function total_sales_price()
    {
        return $this->sales_items()->sum('product_final_price');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function serviceProducts()
    {
        return $this->hasMany(ServiceProduct::class, 'visit_id');
    }
    public function service_requirment_batt_spear_parts()
    {
        return $this->hasMany(ServiceRequirementsofbatteryAndSpare::class, 'visit_id');
    }
    public function service_maintainces()
    {
        return $this->hasOne(ServiceMaintaince::class, 'visit_id');
    }
    public function files()
    {
        return $this->hasMany(GlobalImage::class, 'visit_id');
    }
    public function offer_quotation()
    {
        return $this->belongsTo(CustomerOffer::class,'offer_id');
    }
    public function invoice()
    {
        return $this->belongsTo(Invoice::class,'invoice_id');
    }
}
