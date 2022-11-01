<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Call extends Model
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
    public function customer_office()
    {
        return $this->belongsTo(OfficeLocation::class,'customer_location_id');
    }
    public function approvedBy()
    {
        return $this->belongsTo(User::class,'approved_by');
    }
    public function addedBy()
    {
        return $this->belongsTo(User::class,'addedBy_id');
    }
    public function refferTeamHeads()
    {
        return $this->belongsToMany(Employee::class,'call_reference_pevots','call_id','team_admin_employee_id');
    }
    public function visitPlan()
    {
        return $this->hasMany(VisitPlan::class,'call_id');
    }
    public function temp_products()
    {
        return $this->hasMany(CallToSendProductRequest::class,'call_id')->where('status','temp');
    }
    public function call_products()
    {
        return $this->hasMany(CallToSendProductRequest::class,'call_id');
    }
    public function sent_product()
    {
        return (bool) $this->call_products()->where('sent',true)->count();
    }
    public function not_received_product()
    {
        return (bool) $this->call_products()->where('received',false)->count();
    }
    public function submited()
    {
        return (bool) $this->call_products()->where('submited',true)->count();
    }
    
}
