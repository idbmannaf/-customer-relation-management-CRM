<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    use HasFactory;
    public function addedBy()
    {
        return $this->belongsTo(User::class,'addedBy_id');
    }
}
