<?php

namespace App\Imports;

use App\Models\Designation;
use Maatwebsite\Excel\Concerns\ToModel;

class DesignationImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if ($row[0]) {
            $designation = Designation::where('title','like','%'.$row[0].'%')->first();
            if (!$designation)
               {
                   $designation = new Designation;
                   $designation->title = $row[0];
                   $designation->save();

               }
        }
    }
}
