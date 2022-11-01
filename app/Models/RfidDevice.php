<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RfidDevice extends Model
{
    use HasFactory;
    public function office_location()
    {
        return $this->belongsTo(OfficeLocation::class,'company_office_location_id');
    }
}
