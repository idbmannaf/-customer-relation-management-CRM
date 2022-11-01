<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->hasOne(Customer::class, 'user_id');
    }
    public function teams()
    {
        return $this->belongsToMany(Team::class,'user_teams','user_id','team_id');
    }
    public function officeLocation()
    {
        return $this->belongsTo(OfficeLocation::class,'office_location_id');
    }
    public function locations()
    {
        return $this->hasMany(UserLocation::class,'user_id');
    }
    public function latestLocation()
    {
        return $this->locations()->latest()->first();
    }
    public function team_roles()
    {
       return $this->hasMany(TeamRole::class,'user_id');
    }
    public function hasTeamRoleOf($team)
    {
        return (bool) $this->team_roles()->where('team_id', $team->id)->first();

    }
    public function hasTeamAccess()
    {
        return (bool) $this->team_roles()->where('user_id',Auth::id())->first();
    }
    public function fi()
    {
        if ($this->avater) {
            return $this->avater;
        }else{
            return 'dummy.webp';
        }
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class,'user_id');
    }
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }
    public function hasMyRole($role)
    {
        if ($role == 'employee') {
            return (bool) $this->employee()->count();
        }elseif($role == 'customer'){
            return (bool) $this->customer()->count();
        }else{
            return false;
        }
    }
    public function hasCompanyOf()
    {
        return (bool) $this->employee()->company->id;
    }
    // public function hasTodayAttendance($date,$user)
    // {
    //    return (bool) $this->attendances()->whereDate('date',$date)->where('user_id',$user)->first();
    // }
    public function todayAttendance($date,$user,$type='')
    {
       return $this->attendances()->whereDate('date',$date)->where('user_id',$user)->first();
    }
    public function TTAttendance()
    {
        return $this->hasMany(Attendance::class,'user_id')->whereDate('date',date("Y-m-d"));
    }



}
