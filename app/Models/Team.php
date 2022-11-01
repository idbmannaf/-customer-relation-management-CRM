<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    public function users()
    {
        return $this->belongsToMany(User::class,'user_teams','team_id','user_id');
    }
    public function team_roles()
    {
        return $this->belongsToMany(User::class,'team_roles','team_id','user_id')->withPivot(['role_name','id']);
    }

    public function teamAdmin()
    {
        return $this->team_roles()->wherePivot('role_name', 'admin')->get();
    }
    public function teamMember()
    {
        return $this->team_roles()->wherePivot('role_name', 'member')->paginate(20);
    }
    public function hasAnyRole($user)
    {
        return (bool) $this->team_roles()->where('user_id',$user)->first();
    }
    public function hasAdminRole($user)
    {
        return (bool) $this->team_roles()->where('user_id',$user)->where('role_name','admin')->first();
    }
    public function hasMemberRole($user)
    {
        return (bool) $this->team_roles()->where('user_id',$user)->where('role_name','member')->first();
    }
    public function hasNoRole($user)
    {
        return (bool) $this->team_roles()->where('user_id',$user)->where('role_name','no_role')->first();
    }
    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }

}
