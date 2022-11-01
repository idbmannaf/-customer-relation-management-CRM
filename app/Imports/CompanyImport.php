<?php

namespace App\Imports;

use App\Models\Company;
use Maatwebsite\Excel\Concerns\ToModel;

class CompanyImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if ($row[0]) {
            $company = Company::where('name','like','%'.$row[0].'%')->first();
            if (!$company)
               {
                   $company = new Company;
                   $company->name = $row[0];
                   $company->active =1;
                   $company->save();

               }
        }
    }
}
