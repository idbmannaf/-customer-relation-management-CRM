<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerCompany extends Model
{
    use HasFactory;
    public function offices()
    {
        return $this->hasMany(OfficeLocation::class, 'customer_company_id');
    }
    public function visitors()
    {
        return $this->hasManyThrough(

            UserLocation::class,
            OfficeLocation::class,
        );
    }
    public function addedBy()
    {
        return $this->belongsTo(User::class,'addedBy_id');
    }
}
