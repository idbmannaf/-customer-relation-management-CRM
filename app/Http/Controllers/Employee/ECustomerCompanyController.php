<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CustomerCompany;
use App\Models\District;
use App\Models\Employee;
use App\Models\OfficeLocation;
use App\Models\Upazila;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ECustomerCompanyController extends Controller
{
    use EmployeeTrait;
    public function CustomerCompany(Request $request)
    {
        menuSubmenu('customerCompany', 'customerCompanies');
        menuSubmenu('customerCompany', 'addCustomerCompany');
        $employee = Auth::user()->employee;
        $companies = CustomerCompany::where('active', true)->paginate(50);

        $addeByArray = $this->addedBy();

        return view('employee.customerComapany.customerCompany', compact('companies', 'addeByArray', 'employee'));
    }

    public function addCustomerCompany(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'active' => 'nullable',
        ]);
        $company = new CustomerCompany;
        $company->name = $request->name;
        $company->address = $request->address;
        $company->active = $request->active ? 1 : 0;
        $company->addedBy_id = Auth::id();
        $company->save();
        return redirect()->back()->with('success', ' Customer Company Added successfuly');
    }
    public function updateCustomerCompany(Request $request, CustomerCompany $customerCompany)
    {
        $request->validate([
            'name' => 'required',
            'active' => 'nullable',
        ]);
        $customerCompany->name = $request->name;
        $customerCompany->address = $request->address;
        $customerCompany->active = $request->active ? 1 : 0;
        $customerCompany->editedBy_id = Auth::id();
        $customerCompany->save();
        return redirect()->back()->with('success', 'Customer Company Updated successfuly');
    }
    public function customerCompanyOffice(Request $request, CustomerCompany $customer_company)
    {

        $employee = Auth::user()->employee;
        if ($employee->team_admin) {

            $myEmployees = $employee->myTeamMembers()->pluck('id');

            $addeByArray = Employee::whereIn('id', $myEmployees)->orWhere('id', $employee->id)->pluck('user_id')->toArray();
        } else {
            $addeByArray = Employee::where('id', $employee->id)->pluck('user_id')->toArray();
        }

        return view('employee.customerComapany.customerOffices', compact('customer_company', 'addeByArray'));
    }

    public function customerCompanyOfficeAdd(Request $request, CustomerCompany $customer_company)
    {

        if ($request->isMethod('get')) {
            $divisions = DB::table('divisions')->select(['id', 'name'])->orderBy('name')->get();
            $districts = DB::table('districts')->select(['id', 'name', 'division_id'])->orderBy('name')->get();
            $thanas = DB::table('upazilas')->select(['id', 'name', 'district_id', 'division_id'])->orderBy('name')->get();

            return view('employee.customerComapany.customerOfficesCreate', compact('divisions', 'districts', 'thanas', 'customer_company'));
        }
        if ($request->isMethod('post')) {
            $request->validate([
                'title' => 'required',
                'location' => 'required',
                'lat' => 'required',
                'lng' => 'required',
                'division' => 'nullable',
                'district' => 'nullable',
                'thana' => 'nullable',
                'featured_image' => 'nullable',
                'active' => 'nullable',
            ]);

            $office_location = new OfficeLocation;
            $office_location->title = $request->title;
            $office_location->google_location = $request->location;
            $office_location->lat = $request->lat;
            $office_location->lng = $request->lng;
            $office_location->division_id = $request->division;
            $office_location->district_id = $request->district;
            $office_location->thana_id = $request->thana;
            $office_location->office_start_time = $request->office_start_time;
            $office_location->office_end_time = $request->office_end_time;
            $office_location->customer_company_id = $customer_company->id;
            $office_location->active = $request->active ? 1 : 0;
            $office_location->addedBy_id = Auth::id();
            $office_location->type = 'customer';
            $office_location->save();

            if ($request->hasFile('featured_image')) {
                $file = $request->featured_image;
                $ext = "." . $file->getClientOriginalExtension();
                $imageName = Str::slug($office_location->title) . time() . $ext;
                Storage::disk('public')->put('officeLocations/' . $imageName, File::get($file));
                $office_location->featured_image = $imageName;
            }
            $office_location->save();
            return redirect()->back()->with('success', 'Customer Office Location Added Successfully');
        }
    }
    public function customerCompanyOfficeEdit(Request $request, CustomerCompany $customer_company, OfficeLocation $office)
    {
        $employee = Auth::user()->employee;
        if (!in_array($office->addedBy_id, $this->addedBy())) {
            abort(403);
        }

        if ($request->isMethod('get')) {
            $divisions = DB::table('divisions')->select(['id', 'name'])->orderBy('name')->get();
            $districts = DB::table('districts')->select(['id', 'name', 'division_id'])->orderBy('name')->get();
            $thanas = DB::table('upazilas')->select(['id', 'name', 'district_id', 'division_id'])->orderBy('name')->get();
            $selected_districts = District::where('division_id', $office->division_id)->get();
            $selected_thanas = Upazila::where('district_id', $office->district_id)->get();
            return view('employee.customerComapany.customerOfficesEdit', compact('divisions', 'districts', 'thanas', 'customer_company', 'office', 'selected_districts', 'selected_thanas', 'employee'));
        }
        if ($request->isMethod('patch')) {
            if ($employee->team_admin || ($office->active == false)) {
                $request->validate([
                    'title' => 'required',
                    'location' => 'required',
                    'lat' => 'required',
                    'lng' => 'required',
                    'division' => 'nullable',
                    'district' => 'nullable',
                    'thana' => 'nullable',
                    'featured_image' => 'nullable',
                    'active' => 'nullable',
                ]);
            } else {
                $request->validate([
                    'title' => 'required',
                    'division' => 'nullable',
                    'district' => 'nullable',
                    'thana' => 'nullable',
                    'featured_image' => 'nullable',
                    'active' => 'nullable',
                ]);
            }


            if ($employee->team_admin || ($office->active == false)) {
                $office->google_location = $request->location;
                $office->lat = $request->lat;
                $office->lng = $request->lng;
            }

            $office->title = $request->title;
            $office->division_id = $request->division;
            $office->district_id = $request->district;
            $office->thana_id = $request->thana;
            $office->office_start_time = $request->office_start_time;
            $office->office_end_time = $request->office_end_time;
            $office->customer_company_id = $customer_company->id;
            $office->active = $request->active ? 1 : 0;
            $office->editedBy_id = Auth::id();
            $office->type = 'customer';
            $office->save();

            if ($request->hasFile('featured_image')) {
                $old_file = 'officeLocations/' . $office->featured_image;
                if (Storage::disk('public')->exists($old_file)) {
                    Storage::disk('public')->delete($old_file);
                }
                $file = $request->featured_image;
                $ext = "." . $file->getClientOriginalExtension();
                $imageName = Str::slug($office->title) . time() . $ext;
                Storage::disk('public')->put('officeLocations/' . $imageName, File::get($file));
                $office->featured_image = $imageName;
            }
            $office->save();
            return redirect()->back()->with('success', 'Customer Office Location Updated Successfully');
        }
    }
}
