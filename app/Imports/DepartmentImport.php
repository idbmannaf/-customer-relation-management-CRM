<?php

namespace App\Imports;

use App\Models\Department;
use Maatwebsite\Excel\Concerns\ToModel;

class DepartmentImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if ($row[0]) {
            $department = Department::where('title','like','%'.$row[0].'%')->first();
            if (!$department)
               {
                   $department = new Department;
                   $department->title = $row[0];
                   $department->save();

               }
        }
    }
}
