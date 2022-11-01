<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\CollectionHistory;
use App\Models\Company;
use App\Models\Customer;
use App\Models\CustomerCompany;
use App\Models\District;
use App\Models\Division;
use App\Models\Employee;
use App\Models\OfficeLocation;
use App\Models\Upazila;
use App\Models\User;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Storage;

class ECustomerController extends Controller
{

    use EmployeeTrait;
    public function index(Request $request)
    {
        menuSubmenu('customers', 'myCustomers');
        $user = Auth::user();
        $employee = $user->employee;

        $customers = $this->my_customers()->paginate(50);

        $q = '';
        if ($request->ajax()) {
            return view('employee.customers.ajax.mycustomerAjax', [
                'customers' => $customers,
                'employee' => $employee,
                'q' => $q
            ])->render();
        }
        // dd($customers);

        return view('employee.customers.myCustomers', [
            'customers' => $customers,
            'employee' => $employee,
            'q' => $q
        ]);
    }


    public function myCustomerSearch(Request $request)
    {
        $q = $request->q;
        $type = 'customer';
        $user = Auth::user();
        $employee = $user->employee;

        if ($q) {
            $customers = Customer::whereIn('id', $this->my_customers()->pluck('id'))
                ->where(function ($qq) use ($q) {
                    $qq->where('customer_name', 'like', '%' . $q . '%');
                    $qq->orWhere('company_name', 'like', '%' . $q . '%');
                    $qq->orWhereHas('employee', function ($query) use ($q) {
                        $query->where('name', 'like', '%' . $q . '%');
                        $query->orWhere('employee_id', 'like', '%' . $q . '%');
                    });
                    $qq->orWhere('id', $q);
                    $qq->orWhere('email', 'like', '%' . $q . '%');
                    $qq->orWhere('mobile', 'like', '%' . $q . '%');
                    $qq->orWhere('customer_code', 'like', '%' . $q . '%');
                    $qq->orWhere('phone', 'like', '%' . $q . '%');
                })
                ->with('user')
                ->latest()
                ->paginate(50);

        } else {
            $customers =  $customers = $this->my_customers()->paginate(50);
        }
        // dump($customers);
        // dd($this->my_customers());
        // $customers = Customer::whereIn('id', $this->my_customers()->pluck('id'))
        //         ->where(function ($qq) use ($q) {
        //             $qq->where('customer_name', 'like', '%' . $q . '%');
        //             $qq->orWhere('company_name', 'like', '%' . $q . '%');
        //             $qq->orWhereHas('employee', function ($query) use ($q) {
        //                 $query->where('name', 'like', '%' . $q . '%');
        //                 $query->orWhere('employee_id', 'like', '%' . $q . '%');
        //             });
        //             $qq->orWhere('id', $q);
        //             $qq->orWhere('email', 'like', '%' . $q . '%');
        //             $qq->orWhere('mobile', 'like', '%' . $q . '%');
        //             $qq->orWhere('customer_code', 'like', '%' . $q . '%');
        //             $qq->orWhere('phone', 'like', '%' . $q . '%');
        //         })
        //         ->with('user')
        //         ->latest()

        //         ->paginate(50);
        return view('employee.customers.ajax.mycustomerAjax', compact('employee', 'customers', 'q'))->render();
    }
    public function create(Request $request)
    {
        $employee = Auth::user()->employee;
        $divisions = DB::table('divisions')->select(['id', 'name'])->orderBy('name')->get();
        $districts = DB::table('districts')->select(['id', 'name', 'division_id'])->orderBy('name')->get();
        $thanas = DB::table('upazilas')->select(['id', 'name', 'district_id', 'division_id'])->orderBy('name')->get();

        if ($employee->team_admin) {
            $my_employees = $employee->myTeamMembers;
        } else {
            $my_employees = [
                'id' => $employee->id,
                'name' => $employee->name,
            ];
        }

        return view('employee.customers.myCustomersCreate', compact('employee', 'my_employees', 'divisions', 'districts', 'thanas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,username',
            'customer_code' => 'required|unique:customers,customer_code',
            'customer_name' => 'required',
            'client_address' => 'nullable',
            'division' => 'nullable',
            'district' => 'nullable',
            'thana' => 'nullable',
            'phone' => 'nullable',
            'area_id' => 'nullable',
            'contact_person_name' => 'nullable',
            'designation' => 'nullable',
            'mobile' => 'nullable',
            'ledger_balance' => 'nullable',
            'active' => 'nullable',
        ]);

        if ($request->employee) {
            $employee = Employee::find($request->employee);
        } else {
            $employee = Auth::user()->employee;
        }
        $user = new User;
        $user->name = $request->customer_name;
        $user->username = $request->email;
        $randPas = rand(10000, 555555);
        $user->password = Hash::make($randPas);
        $user->temp_password = $randPas;
        $user->track = true;
        $user->save();
        //Create Company create Start
        $customer_company = CustomerCompany::where('name', "like", "%" . $request->customer_name . "%")->first();
        if (!$customer_company) {
            $customer_company = new CustomerCompany;
            $customer_company->name = $request->customer_name;
            $customer_company->address = $request->client_address;
            $customer_company->active = true;
            $customer_company->save();
        }
        //Create Company create Start

        //Create Office create Start
        $customer_office = OfficeLocation::where('type','customer')->where('title',$customer_company->name)->first();
        if (!$customer_office) {
            $customer_office = new OfficeLocation;
            $customer_office->customer_company_id = $customer_company->id;
            $customer_office->title = $customer_company->address;
            $customer_office->save();
        }
        //Create Office create end

        $customer = new Customer;
        $customer->user_id = $user->id;
        $customer->employee_id = $employee->id;
        $customer->company_id = $customer_company->id;
        $customer->employee_name = $employee->name;
        $customer->customer_code = $request->customer_code;
        $customer->customer_name = $request->customer_name;
        $customer->client_address = $request->client_address;
        $customer->phone = $request->phone;
        $customer->area_id = $request->area_id;
        $customer->customer_type = $request->customer_type;
        $customer->contact_person_name = $request->contact_person_name;
        $customer->designation = $request->designation;
        $customer->email = $user->username;
        $customer->mobile = $request->mobile;
        $customer->ledger_balance = $request->ledger_balance ?? 0.00;
        $customer->active = $request->active ? 1 : 0;
        $customer->addedBy_id = Auth::id();

        $customer->area = $request->area;
        if ($request->division) {
            $customer->division = Division::find($request->division)->name;
        }
        if ($request->district) {
            $customer->district = District::find($request->district)->name;
        }
        if ($request->thana) {
            $customer->thana = Upazila::find($request->thana)->name;
        }
        $customer->save();
        return back()->with('success', 'New Customer Account Successfully Created.');
    }
    public function show(Customer $customer)
    {
        //
    }
    public function edit(Customer $customer)
    {
        $employee = Auth::user()->employee;
        if ($employee->team_admin) {
            $my_employees = $employee->myTeamMembers;
        } else {
            $my_employees = [
                'id' => $employee->id,
                'name' => $employee->name,
            ];
        }
        $divisions = DB::table('divisions')->select(['id', 'name'])->orderBy('name')->get();
        $districts = DB::table('districts')->select(['id', 'name', 'division_id'])->orderBy('name')->get();
        $thanas = DB::table('upazilas')->select(['id', 'name', 'district_id', 'division_id'])->orderBy('name')->get();
        $div = Division::where('name', 'like', '%' . $customer->division . '%')->first();
        $dis = District::where('name', 'like', '%' . $customer->district . '%')->first();

        $selectedDistricts = District::where('division_id', $div->id)->get();
        $selectedThanas = Upazila::where('district_id', $dis->id)->get();
        return view('employee.customers.myCustomersEdit', compact('customer', 'employee', 'divisions', 'districts', 'thanas', 'selectedDistricts', 'selectedThanas', 'my_employees'));
    }

    public function update(Request $request, Customer $customer)
    {
        $user = $customer->user;
        $request->validate([
            'email' => 'required|email|unique:users,username,' . $user->id,
            'customer_code' => 'required|unique:customers,customer_code,' . $customer->id,
            'customer_name' => 'required',
            'client_address' => 'nullable',
            'division' => 'nullable',
            'district' => 'nullable',
            'thana' => 'nullable',
            'phone' => 'nullable',
            'area_id' => 'nullable',
            'contact_person_name' => 'nullable',
            'designation' => 'nullable',
            'mobile' => 'nullable',
            'ledger_balance' => 'nullable',
            'active' => 'nullable',
        ]);

        if ($request->employee) {
            $employee = Employee::find($request->employee);
        } else {
            $employee = Auth::user()->employee;
        }

        if ($request->email != $customer->user->username) {
            $user->name = $request->customer_name;
            $user->username = $request->email;
            $user->track = true;
            $user->save();
        }
        //Create Company create Start
        $customer_company = CustomerCompany::where('name', "like", "%" . $request->customer_name . "%")->first();
        if (!$customer_company) {
            $customer_company = new CustomerCompany;
            $customer_company->name = $request->customer_name;
            $customer_company->address = $request->client_address;
            $customer_company->active = true;
            $customer_company->save();
        }
        //Create Company create End

        $customer->user_id = $user->id;
        $customer->employee_id = $employee->id;
        $customer->company_id = $customer_company->id;
        $customer->employee_name = $employee->name;
        $customer->customer_code = $request->customer_code;
        $customer->customer_name = $request->customer_name;
        $customer->client_address = $request->client_address;
        $customer->phone = $request->phone;
        $customer->area_id = $request->area_id;
        $customer->customer_type = $request->customer_type;
        $customer->contact_person_name = $request->contact_person_name;
        $customer->designation = $request->designation;
        $customer->email = $user->username;
        $customer->mobile = $request->mobile;
        $customer->ledger_balance = $request->ledger_balance ?? 0.00;
        $customer->active = $request->active ? 1 : 0;
        $customer->addedBy_id = Auth::id();

        $customer->area = $request->area;
        if ($request->division) {
            $customer->division = Division::find($request->division)->name;
        }
        if ($request->district) {
            $customer->district = District::find($request->district)->name;
        }
        if ($request->thana) {
            $customer->thana = Upazila::find($request->thana)->name;
        }
        $customer->save();
        return back()->with('success', 'Customer Account Successfully Created.');
    }
    public function destroy(Customer $customer)
    {
        //
    }
    public function othersCustomers(Request $request)
    {
        menuSubmenu('customers', 'othersCustomers');
        $employee = Auth::user()->employee;
        if (!$employee->team_admin) {
            abort(403, 'Permission Denied');
        }
        $others_customer = Customer::whereDoesntHave('employee')->paginate(20);
        $q = '';
        if ($request->ajax()) {
            return view('employee.customers.ajax.myOtherCustomerAjax',  [
                'others_customer' => $others_customer,
                'employee' => $employee,
                'q' => $q
            ])->render();
        }

        return view('employee.customers.otherCustomers', [
            'others_customer' => $others_customer,
            'employee' => $employee,
            'q' => $q
        ]);
    }

    public function othersCustomerSearch(Request $request)
    {
        $q = $request->q;
        $type = 'customer';
        $user = Auth::user();
        $employee = $user->employee;
        $customers = Customer::whereDoesntHave('employee')->where('customer_name', 'like', '%' . $q . '%')
            ->orWhere('company_name', 'like', '%' . $q . '%')
            ->orWhere('id', $q)
            ->orWhere('email', 'like', '%' . $q . '%')
            ->orWhere('mobile', 'like', '%' . $q . '%')
            ->orWhere('customer_code', 'like', '%' . $q . '%')
            ->orWhere('phone', 'like', '%' . $q . '%')
            ->with('user')
            ->latest()
            // ->where('employee_id', null)
            ->paginate(30);

        return view('employee.customers.ajax.mycustomerAjax', compact('employee', 'customers', 'q'))->render();
    }
    public function othersCustomerEdit(Request $request, Customer $customer)
    {
        $employee = Auth::user()->employee;
        if (!$employee->team_admin) {
            abort(403, 'Permission Denied');
        }

        $my_employees = Employee::whereIn('id', $this->alEmployees())->get();
        $divisions = DB::table('divisions')->select(['id', 'name'])->orderBy('name')->get();
        $districts = DB::table('districts')->select(['id', 'name', 'division_id'])->orderBy('name')->get();
        $thanas = DB::table('upazilas')->select(['id', 'name', 'district_id', 'division_id'])->orderBy('name')->get();
        $div = Division::where('name', 'like', '%' . $customer->division . '%')->first();
        $dis = District::where('name', 'like', '%' . $customer->district . '%')->first();

        $selectedDistricts = District::where('division_id', $div->id)->get();
        $selectedThanas = Upazila::where('district_id', $dis->id)->get();
        return view('employee.customers.myCustomersEdit', compact('customer', 'employee', 'divisions', 'districts', 'thanas', 'selectedDistricts', 'selectedThanas', 'my_employees'));
    }
    public function othersCustomerUpdate(Request $request, Customer $customer)
    {
        $employee = Auth::user()->employee;
        if (!$employee->team_admin) {
            abort(403, 'Permission Denied');
        }
        $user = $customer->user;
        $request->validate([
            'email' => 'required|email|unique:users,username,' . $user->id,
            'customer_code' => 'required|unique:customers,customer_code,' . $customer->id,
            'customer_name' => 'required',
            'client_address' => 'nullable',
            'division' => 'nullable',
            'district' => 'nullable',
            'thana' => 'nullable',
            'phone' => 'nullable',
            'area_id' => 'nullable',
            'contact_person_name' => 'nullable',
            'designation' => 'nullable',
            'mobile' => 'nullable',
            'ledger_balance' => 'nullable',
            'active' => 'nullable',
        ]);

        if ($request->employee) {
            $employee = Employee::find($request->employee);
        } else {
            $employee = Auth::user()->employee;
        }

        if ($request->email != $customer->user->username) {
            $user->name = $request->customer_name;
            $user->username = $request->email;
            $user->track = true;
            $user->save();
        }
        //Create Company create Start
        $customer_company = CustomerCompany::where('name', "like", "%" . $request->customer_name . "%")->first();
        if (!$customer_company) {
            $customer_company = new CustomerCompany;
            $customer_company->name = $request->customer_name;
            $customer_company->address = $request->client_address;
            $customer_company->active = true;
            $customer_company->save();
        }
        //Create Company create End

        $customer->user_id = $user->id;
        $customer->employee_id = $employee->id;
        $customer->company_id = $customer_company->id;
        $customer->employee_name = $employee->name;
        $customer->customer_code = $request->customer_code;
        $customer->customer_name = $request->customer_name;
        $customer->client_address = $request->client_address;
        $customer->phone = $request->phone;
        $customer->area_id = $request->area_id;
        $customer->customer_type = $request->customer_type;
        $customer->contact_person_name = $request->contact_person_name;
        $customer->designation = $request->designation;
        $customer->email = $user->username;
        $customer->mobile = $request->mobile;
        $customer->ledger_balance = $request->ledger_balance ?? 0.00;
        $customer->active = $request->active ? 1 : 0;
        $customer->addedBy_id = Auth::id();

        $customer->area = $request->area;
        if ($request->division) {
            $customer->division = Division::find($request->division)->name;
        }
        if ($request->district) {
            $customer->district = District::find($request->district)->name;
        }
        if ($request->thana) {
            $customer->thana = Upazila::find($request->thana)->name;
        }
        $customer->save();
        return back()->with('success', 'Customer Account Successfully Created.');
    }
    public function myCustomerOffices(Request $request, Customer $customer)
    {

        $office_locations = OfficeLocation::where('customer_company_id', $customer->company_id)->latest()->paginate(100);
        $q = '';
        if ($request->ajax()) {
            return view('employee.customers.offices.ajax.customerOfficelocationAjax', compact('office_locations', 'customer', 'q'))->render();
        }
        return view('employee.customers.offices.myCustomerOffices', compact('office_locations', 'customer', 'q'));
    }
    public function customerCompanyOfficeSearch(Request $request)
    {
        $q = $request->q;
        $type = 'customer';
        $customer = Customer::find($request->customer);
        $office_locations = OfficeLocation::where('type', 'customer')
            ->where('customer_company_id', $customer->company_id)
            ->where('type', '!=', null)
            ->where('title', 'like', '%' . $q . '%')
            ->orWhereHas('customer_company', function ($query) use ($q) {
                $query->where('name', 'like', '%' . $q . '%');
            })
            ->orWhere('booth_id', 'like', '%' . $q . '%')
            ->orWhere('booth_name', 'like', '%' . $q . '%')
            ->orWhere('item_code', 'like', '%' . $q . '%')
            ->latest()
            ->paginate(100);

        return view('employee.customers.offices.ajax.customerOfficelocationAjax', compact('office_locations', 'q'))->render();
    }

    public function myCustomerOfficeAdd(Request $request, Customer $customer)
    {
        // menuSubmenu('officeLocation', 'createLocations');
        $divisions = DB::table('divisions')->select(['id', 'name'])->orderBy('name')->get();
        $districts = DB::table('districts')->select(['id', 'name', 'division_id'])->orderBy('name')->get();
        $thanas = DB::table('upazilas')->select(['id', 'name', 'district_id', 'division_id'])->orderBy('name')->get();
        $customer_companies = CustomerCompany::where('active', true)->get();
        return view('employee.customers.offices.customerOfficeCreate', compact('divisions', 'districts', 'thanas', 'customer_companies', 'customer'));
    }
    public function myCustomerOfficeStore(Request $request, Customer $customer)
    {
        if (!$customer->company_id) {
            return redirect()->back()->with('warning', 'Company Not Found');
        }
        $request->validate([
            'title' => 'required',
            'location' => 'nullable',
            'lat' => 'nullable',
            'lng' => 'nullable',
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
        $office_location->customer_company_id = $customer->company_id;
        $office_location->address = $request->address;
        $office_location->serial_no = $request->serial_no;
        $office_location->start_date = $request->start_date;
        $office_location->end_date = $request->end_date;
        $office_location->amc_number = $request->amc_number;
        $office_location->billing_period = $request->billing_period;
        $office_location->item_code = $request->item_code;
        $office_location->location_type = $request->location_type;
        $office_location->booth_id = $request->booth_id;
        $office_location->booth_name = $request->booth_name;
        $office_location->mobile_number = $request->mobile_number;
        $office_location->ups_brand = $request->ups_brand;
        $office_location->model = $request->model;
        $office_location->capacity = $request->capacity;
        $office_location->battery_brand = $request->battery_brand;
        $office_location->battery_ah = $request->battery_ah;
        $office_location->battery_qty = $request->battery_qty;
        $office_location->installation_date = $request->installation_date;
        $office_location->warrenty_exipred_date = $request->warrenty_exipred_date;
        $office_location->amc_amount_per_month = $request->amc_amount_per_month;

        $office_location->active = $request->active ? 1 : 0;
        $office_location->asset = $request->asset ? 1 : 0;
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


    public function myCustomerOfficeEdit(Request $request, Customer $customer, OfficeLocation $office)
    {
        // menuSubmenu('officeLocation', 'createLocations');
        $divisions = DB::table('divisions')->select(['id', 'name'])->orderBy('name')->get();
        $districts = DB::table('districts')->select(['id', 'name', 'division_id'])->orderBy('name')->get();
        $thanas = DB::table('upazilas')->select(['id', 'name', 'district_id', 'division_id'])->orderBy('name')->get();
        $selected_districts = District::where('division_id', $office->division_id)->get();
        $selected_thanas = Upazila::where('district_id', $office->district_id)->get();
        $customer_companies = CustomerCompany::where('active', true)->get();
        return view('employee.customers.offices.customerOfficeEdit', compact('divisions', 'districts', 'thanas', 'customer_companies', 'office', 'selected_districts', 'selected_thanas', 'customer'));
    }

    public function myCustomerOfficeUpdate(Request $request, Customer $customer, OfficeLocation $office)
    {
        $request->validate([
            'title' => 'required',
            'location' => 'nullable',
            'lat' => 'nullable',
            'lng' => 'nullable',
            'division' => 'nullable',
            'district' => 'nullable',
            'thana' => 'nullable',
            'featured_image' => 'nullable',
            'active' => 'nullable',
        ]);

        $office->title = $request->title;
        $office->google_location = $request->location;
        $office->lat = $request->lat;
        $office->lng = $request->lng;
        $office->division_id = $request->division;
        $office->district_id = $request->district;
        $office->thana_id = $request->thana;
        $office->office_start_time = $request->office_start_time;
        $office->office_end_time = $request->office_end_time;
        $office->address = $request->address;
        $office->serial_no = $request->serial_no;
        $office->start_date = $request->start_date;
        $office->end_date = $request->end_date;
        $office->amc_number = $request->amc_number;
        $office->billing_period = $request->billing_period;
        $office->item_code = $request->item_code;
        $office->location_type = $request->location_type;
        $office->booth_id = $request->booth_id;
        $office->booth_name = $request->booth_name;
        $office->mobile_number = $request->mobile_number;
        $office->ups_brand = $request->ups_brand;
        $office->model = $request->model;
        $office->capacity = $request->capacity;
        $office->battery_brand = $request->battery_brand;
        $office->battery_ah = $request->battery_ah;
        $office->battery_qty = $request->battery_qty;
        $office->installation_date = $request->installation_date;
        $office->warrenty_exipred_date = $request->warrenty_exipred_date;
        $office->amc_amount_per_month = $request->amc_amount_per_month;

        $office->asset = $request->asset ? 1 : 0;
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
