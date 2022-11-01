<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePayment extends Model
{
    use HasFactory;
    public function paidBy()
    {
        return $this->belongsTo(User::class,'paid_by');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }
}
