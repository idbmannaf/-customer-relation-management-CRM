<?php

namespace App\Imports;

use App\Models\Company;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class EmployeeImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function startRow(): int
    {
        return 2;
    }
    public function model(array $row)
    {

        if ($row[0]) {
            $user = User::where('username', $row[0])->first();
            if (!$user) {
                $user = new User;
                $user->username = $row[0];
                $user->name = $row[1];
                $user->email = $row[0] . "@gmail.com";
                $user->password = Hash::make($row[8]);
                $user->temp_password = $row[8];
                $user->track = 1;
                $user->attendance = 1;
                $user->save();

                $department = Department::where('title', 'like', "%" . $row[4] . "%")->first();
                if (!$department) {
                    $department = new Department;
                    $department->title = $row[4];
                    $department->save();
                }
                $designation = Designation::where('title', 'like', "%" . $row[3] . "%")->first();
                if (!$designation) {
                    $designation = new Designation;
                    $designation->title = $row[3];
                    $designation->save();
                }
                $company = Company::where('name', 'like', "%" . $row[5] . "%")->first();
                if (!$company) {
                    $company = new Company;
                    $company->name = $row[5];
                    $company->active = 1;
                    $company->save();
                }

                $joining_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[2])->format('Y-m-d');


                if ($row[7]) {
                    $team_head = Employee::where('name', 'like', "%" . $row[7] . "%")->where('team_admin',true)->first();
                    if($team_head){
                        $team_head_id = $team_head->id;
                    }else{
                        $team_head_id = null;
                    }
                }else{
                    $team_head_id = null;
                }

                $employee = new Employee;
                $employee->user_id = $user->id;
                $employee->name = $row[1];
                $employee->joining_date = $joining_date;
                $employee->designation_id = $designation->id;
                $employee->department_id = $department->id;
                $employee->company_id = $company->id;
                $employee->employee_id  = $user->username;
                $employee->team_admin  = $row[6] == 'Team Member' ? 0 : 1;
                $employee->team_admin_id  =$team_head_id;
                $employee->active  = 1;
                $employee->addedBy_id  = Auth::id();
                $employee->save();
            }
        }
    }
}
