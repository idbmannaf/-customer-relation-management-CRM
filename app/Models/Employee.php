<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable =['team_admin_id'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    // A Team Member can get his Team admin  start
    public function teamHead()
    {
        return $this->belongsTo(Employee::class, 'team_admin_id', 'id');
    }
    // A Team Member can get his Team admin  End

    // A Team Admin can get his Team members  start
    public function myTeamMembers()
    {
        // return $this->where('team_admin_id',$this->id)->get();
        return $this->hasMany(Employee::class, 'team_admin_id', 'id');
    }
    // A Team Admin can get his Team members  End


    public function myTotalTeamMembers()
    {
        $this->myTeamMembers()->count();
    }

    //If if am a team member and this post employee is my Team member then return true;
    public function isMyMember($memberEmployeeId)
    {
        if ($this->team_admin) {
            if ($memberEmployeeId == $this->id ||  $this->myTeamMembers()->where('id', $memberEmployeeId)->first()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function refferCalls()
    {
        return $this->belongsToMany(Call::class,'call_reference_pevots','team_admin_employee_id','call_id');
    }
}
