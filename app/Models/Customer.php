<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function company()
    {
        return $this->belongsTo(CustomerCompany::class,'company_id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }



}
