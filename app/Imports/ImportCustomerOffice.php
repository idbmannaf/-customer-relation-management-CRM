<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\CustomerCompany;
use App\Models\District;
use App\Models\Employee;
use App\Models\OfficeLocation;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ImportCustomerOffice implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 5;
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function model(array $row)
    {

        //Customer Check  Start

        if ($row[0]) {
            // $customer_company = CustomerCompany::where('name', $row[0])->first();
            // if (!$customer_company) {
            //     $customer_company = new CustomerCompany;
            //     $customer_company->name = $row[0];
            //     $customer_company->addedBy_id = Auth::id();
            //     $customer_company->save();
            // }

            $c_office = new OfficeLocation;
            $c_office->title = $row[10];
            $c_office->customer_company_id =113;

            //District Check

            $district = District::where('name', $row[8])->first();
            if ($district) {
                $c_office->district_id = $district->id;
            }


            $c_office->address = $row[10];
            $c_office->serial_no = $row[1];

            if ($row[3]) {
                // dd($row[3]);
                // dd(Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3])));
                // $start_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3])->format('Y-m-d');
                // dd($start_date);
                $c_office->start_date = '2019-07-14';

            }
            if ($row[4]) {
                // dd( \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[4]));
                // $end_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[4])->format('Y-m-d');
                // dd($end_date);
                $c_office->end_date = '2024-07-18';
            }



            $c_office->amc_number = $row[2];
            $c_office->billing_period = $row[5];
            $c_office->item_code = $row[6];
            $c_office->location_type = 'booth';
            $c_office->booth_id = $row[7];
            $c_office->booth_name = $row[9];
            $c_office->asset = true;
            $c_office->mobile_number = $row[11];
            $c_office->ups_brand = $row[12];
            $c_office->model = $row[13];
            $c_office->capacity = $row[14];
            $c_office->battery_brand = $row[15];
            $c_office->battery_ah = $row[16];
            $c_office->battery_qty = $row[17] ?? 0;


            if ($row[18]) {
                $installation_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[18])->format('Y-m-d');
                $c_office->installation_date = $installation_date;
            }
            if ($row[19]) {
                $warrenty_exipred_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[19])->format('Y-m-d');
                $c_office->warrenty_exipred_date = $warrenty_exipred_date;
            }


            $c_office->amc_amount_per_month = $row[20] ?? 0.00;
            $c_office->active = true;
            $c_office->addedBy_id = Auth::id();
            $c_office->save();
        }



        //Customer Check  End

    }
}
