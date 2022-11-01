<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\CustomerCompany;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportCustomer implements ToModel
{

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function model(array $row)
    {

        //Customer Check  Start

        if ($row[2]) {
            $customer = Customer::where('customer_code', $row[2])->first();
            if (!$customer) {
                // Customer Employee Check Start
                $employee_id = Employee::where('employee_id', $row[0])->first();

                if ($employee_id) {
                    $employee_id = $employee_id->id;
                } else {
                    $employee_id = null;
                }
                // Customer Employee Check End

                //Customer Company Start
                $customer_company = CustomerCompany::where('name', $row[3])->first();
                if (!$customer_company) {
                    $customer_company = new CustomerCompany;
                    $customer_company->name = $row[3];
                    $customer_company->address = $row[4];
                    $customer_company->active = true;
                    $customer_company->save();
                }

                //Customer Company End

                //Customer User Check Start
                $user = User::where('username', "bob" . $row[2] . "@gmail.com")->first();
                if (!$user) {
                    $user = new User;
                    $user->username = "bob" . $row[2] . "@gmail.com";
                    $randPass = rand(0000000, 9999999);
                    $user->password = Hash::make($randPass);
                    $user->temp_password =  $randPass;
                    $user->track = true;
                    $user->save();
                }

                //Customer User Check End

                //Create Customer Start
                $customer = new Customer;
                $customer->employee_id = $employee_id;
                $customer->user_id =  $user->id;
                $customer->company_id =  $customer_company->id;
                $customer->customer_code = $row[2];
                $customer->customer_name = $row[3];
                $customer->client_address = $row[4];
                $customer->phone = $row[5];
                $customer->area = $row[6];
                $customer->division = $row[7];
                $customer->district = $row[8];
                $customer->customer_type = $row[9];
                $customer->contact_person_name = $row[10];
                $customer->designation = $row[11];
                $customer->mobile = $row[12];
                $customer->ledger_balance = $row[13] ?? 0.00;
                $customer->email = $user->username;
                $customer->active = true;
                $customer->save();
                //Create Customer End

            }
        }



        //Customer Check  End

    }
}
