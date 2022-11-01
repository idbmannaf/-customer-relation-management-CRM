<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionHistory extends Model
{
    use HasFactory;
    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }
    public function collectedBy()
    {
      return $this->belongsTo(Employee::class,'collection_by');
    }


}
