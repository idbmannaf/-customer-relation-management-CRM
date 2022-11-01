<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function office()
    {
        return $this->belongsTo(OfficeLocation::class,'office_location_id');
    }
}
