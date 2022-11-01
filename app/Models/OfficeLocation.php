<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeLocation extends Model
{
    use HasFactory;
    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }
    public function customer_company()
    {
        return $this->belongsTo(CustomerCompany::class,'customer_company_id');
    }
    public function division()
    {
        return $this->belongsTo(Division::class,'division_id');
    }
    public function district()
    {
        return $this->belongsTo(District::class,'district_id');
    }
    public function thana()
    {
        return $this->belongsTo(Upazila::class,'thana_id');
    }
    public function fi()
    {
        if ($this->featured_image) {
            return $this->featured_image;
        }else{
            'logo.png';
        }
    }

    public function employee()
    {
       return $this->hasMany(User::class,'office_location_id');
    }
}
