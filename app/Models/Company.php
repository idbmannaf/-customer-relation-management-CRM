<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Stmt\Return_;

class Company extends Model
{
    use HasFactory;
    public function users()
    {
        return $this->belongsToMany(Company::class,'user_companies','company_id','user_id');
    }
    // public function customers()
    // {
    //     return $this->hasMany(Customer::class,'company_id');
    // }
    public function hasAnyCustomers()
    {
        return (bool) $this->customers()->count();
    }

    public function teams()
    {
        return $this->hasMany(Team::class,'company_id');
    }
    public function officeLocation()
    {
        return $this->hasMany(OfficeLocation::class,'company_id');
    }

    public function employees()
    {
       return $this->hasMany(
           Employee::class,
           'company_id'
        );
    }
    public function customers()
    {
       return $this->hasManyThrough(
           Customer::class,
           Employee::class
        );
    }

    public function team_members()
    {
      return $this->employees()->where('team_admin',false);
    }



}
