<?php

namespace App\Http\Controllers\Employee;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait EmployeeTrait
{
    public function addedBy()
    {
        $employee = Auth::user()->employee;
        if ($employee->team_admin) {
            $employee_ids = $employee->myTeamMembers()->pluck('id');
            $addeByArray = Employee::whereIn('id', $employee_ids)->orWhere('id', $employee->id)->pluck('user_id')->toArray();
        } else {
            $addeByArray = Employee::where('id', $employee->id)->pluck('user_id')->toArray();
        }
        return $addeByArray;
    }


    public function is_my_employee($memberEmployeeId)
    {
        $currentEmployee = Auth::user()->employee;
        if ($currentEmployee->team_admin) {
            if ($memberEmployeeId == $currentEmployee->id ||  $currentEmployee->myTeamMembers()->where('id', $memberEmployeeId)->first()) {
                return true;
            } else {
                return false;
            }
        } elseif ($memberEmployeeId == $currentEmployee->id) {
            return true;
        } else {

            return false;
        }
    }
    public function alEmployees()
    {
        $currentEmployee = Auth::user()->employee;

        if ($currentEmployee->team_admin) {
            $myEmployees = $currentEmployee->myTeamMembers()->pluck('id');
            $employee_ids = Employee::whereIn('id', $myEmployees)->orWhere('id', $currentEmployee->id)->pluck('id')->toArray();
        } else {
            $employee_ids = Employee::where('id', $currentEmployee->id)->pluck('id')->toArray();
        }


        // $currentEmployee = Auth::user()->employee;

        // if ($currentEmployee->team_admin) {
        //     $myEmployees = $currentEmployee->company->employees()->pluck('id');
        //     $employee_ids = Employee::whereIn('id', $myEmployees)->orWhere('id', $currentEmployee->id)->pluck('id')->toArray();
        // } else {
        //     $employee_ids = Employee::where('id', $currentEmployee->id)->pluck('id')->toArray();
        // }

        return $employee_ids;
    }
    public function alEmployeesUserIds()
    {
        $currentEmployee = Auth::user()->employee;
        if ($currentEmployee->team_admin) {
            $myEmployees = $currentEmployee->myTeamMembers()->pluck('id');
            $employee_user_ids = Employee::whereIn('id', $myEmployees)->orWhere('id', $currentEmployee->id)->pluck('user_id')->toArray();
        } else {
            $employee_user_ids = Employee::where('id', $currentEmployee->id)->pluck('user_id')->toArray();
        }

        return $employee_user_ids;
    }
    public function my_customers()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if ($employee->team_admin) {
            if ($employee->company->team_head_access_all_customers || $employee->company->team_member_access_all_customers) {
                $customers = Customer::whereHas('employee')->latest();
            } else {
                $customers = Customer::whereIn('employee_id', $this->alEmployees())->latest();
            }

        } else {
            if ($employee->company->team_member_access_all_customers) {
                $customers = Customer::whereHas('employee')->latest();
            } else {
                $customers = Customer::whereIn('employee_id', $this->alEmployees())->latest();
            }
        }

        return $customers;
    }
}
