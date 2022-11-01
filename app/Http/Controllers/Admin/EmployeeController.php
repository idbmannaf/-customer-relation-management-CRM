<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\EmployeeImport;
use App\Models\Attendance;
use App\Models\Company;
use App\Models\Customer;
use App\Models\CustomerCompany;
use App\Models\Department;
use App\Models\Designation;
use App\Models\District;
use App\Models\Division;
use App\Models\Employee;
use App\Models\EmployeePayment;
use App\Models\OfficeLocation;
use App\Models\Upazila;
use App\Models\User;
use App\Models\UserLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Trend\Trend;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:employee-list', ['only' => ['index']]);
        $this->middleware('permission:employee-search', ['only' => ['searchEmployee']]);
        $this->middleware('permission:employee-add', ['only' => ['store']]);
        $this->middleware('permission:employee-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:employee-delete', ['only' => ['destroy']]);
        $this->middleware('permission:employee-location', ['only' => ['employeeLocation']]);
        $this->middleware('permission:employee-attandance', ['only' => ['employeeAttaendance']]);
        $this->middleware('permission:employee-bulk-upload', ['only' => ['companyOffice']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        menuSubmenu('employees', 'allEmployees');
        $total_employee = Employee::count();
        $employees = Employee::with('user', 'designation', 'department')->latest()->paginate(30);
        $q = '';
        if ($request->ajax()) {
            return  view('admin.employee.ajax.employeeList', compact('employees', 'q'))->render();
        }
        return view('admin.employee.employee', compact('employees', 'q','total_employee'));
    }

    public function searchEmployee(Request $request)
    {
        $q = $request->q;
        $employees = Employee::where('employee_id', 'like', "%" . $q . "%")
            ->orWhereHas('company', function ($query) use ($q) {
                $query->where('name', 'like', "%" . $q . "%");
            })
            ->orWhereHas('designation', function ($query) use ($q) {
                $query->where('title', 'like', "%" . $q . "%");
            })
            ->orWhereHas('department', function ($query) use ($q) {
                $query->where('title', 'like', "%" . $q . "%");
            })
            ->orWhere('name', 'like', "%" . $q . "%")
            ->paginate(30);

        return  view('admin.employee.ajax.employeeList', compact('employees', 'q'))->render();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::where('active', true)->get();
        $designations = Designation::latest()->get();
        $departments = Department::latest()->get();
        $office_locations = OfficeLocation::where('active', true)->get();
        $team_admins = Employee::where('team_admin',true)->get();
        $team_roles = [0=>"Team Member",1=>"Team Admin"];
        return view('admin.employee.createEmployee', compact('companies', 'designations', 'departments', 'office_locations','team_admins','team_roles'));
    }

    public function loadLocation(Request $request)
    {
        $company = Company::find($request->company);
        $office_locations = OfficeLocation::where('company_id', $company->id)->get();
        if (count($office_locations)) {
            return response()->json([
                'success' => true,
                'html' => view('admin.employee.loadLocationAjax', compact('office_locations'))->render(),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'company' => $company->name
            ]);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        if ($request->type == 'bulk') {
            $request->validate([
                'file' => 'required'
            ]);
            if ($request->file->getClientOriginalExtension() != 'xlsx') {
                return redirect()->back()->with('error', "Only xlsx File Allowed");
            }
            $upload = Excel::import(new EmployeeImport(), request()->file('file'),);
            if ($upload) {
                return redirect()->back()->with('success', "Employee Added Successfully");
            }
        }
        $request->validate([
            'name' => 'required',
            'employee_id' => 'required|unique:employees,employee_id',
            'employee_password' => 'required',
            'joining_date' => 'required|date',
            'designation' => 'required',
            'department' => 'required',
            'company' => 'required',
            'rfid' => 'nullable|unique:employees,rfid',
            'team_role' => 'required',
            'track_him' => 'nullable',
            'attendance' => 'nullable',
            'active' => 'nullable',
            'avater' => 'nullable',
        ]);
        $user = User::where('username', $request->employee_id)->first();

        if ($user) {
            return redirect()->back()->with('warning', 'Employe ID Already Exist. try new');
        } else {
            $user = new User;
            $user->username = $request->employee_id;
            $user->name = $request->name;
            $user->password = Hash::make($request->employee_password);
            $user->temp_password = $request->employee_password;
            $user->office_location_id = $request->head_office;
            $user->track = $request->track_him ? 1 : 0;
            $user->attendance = $request->attendance ? 1 : 0;
            if ($request->hasFile('avater')) {
                $cp = $request->file('avater');
                $extension = strtolower($cp->getClientOriginalExtension());
                $randomFileName = $user->username . '_company_' . date('his') . '_' . rand(100, 99) . '.' . $extension;
                Storage::disk('public')->put('users/' . $randomFileName, File::get($cp));
                $user->avater =  $randomFileName;
                // if($company->logo_name)
                // {
                //     $f = 'company/logo/'.$company->logo_name;
                //     if(Storage::disk('upload')->exists($f))
                //     {
                //         Storage::disk('upload')->delete($f);
                //     }
                // }
            }

            $user->save();
        }

        $employee = new Employee;
        $employee->user_id = $user->id;
        $employee->employee_id  = $user->username;
        $employee->mobile = $request->mobile;
        $employee->email = $request->email;
        $employee->rfid = $request->rfid;
        $employee->name = $user->name;
        $employee->joining_date = $request->joining_date;
        $employee->designation_id = $request->designation;
        $employee->department_id = $request->department;
        $employee->company_id = $request->company;
        $employee->team_admin  = $request->team_role;
        $employee->team_admin_id  = $request->team_admin_employee_id;
        $employee->active  = $request->active ? 1 : 0;
        $employee->addedBy_id  = Auth::id();
        $employee->save();
        return redirect()->back()->with('success', 'Employe Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $employee = Employee::where('id', $request->employee)->with('user', 'designation', 'department')->first();
        $companies = Company::where('active', true)->get();
        $designations = Designation::latest()->get();
        $departments = Department::latest()->get();
        $team_admins = Employee::where('team_admin',true)->where('id','!=',$employee->id)->get();
        $team_roles = [0=>"Team Member",1=>"Team Admin"];
        $selected_team_admins = Employee::where('company_id',$employee->company_id)->where('team_admin',1)->where('id','!=',$employee->id)->get();

        return view('admin.employee.editEmployee', compact('employee', 'companies', 'designations', 'departments','team_admins','team_roles','selected_team_admins'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $employee = Employee::find($id);
        $user = $employee->user;

        $request->validate([
            'name' => 'required',
            'employee_id' => 'required|unique:users,username,' . $user->id,
            'joining_date' => 'required|date',
            'designation' => 'required',
            'department' => 'required',
            'company' => 'required',
            'rfid' => 'nullable|unique:employees,rfid,'.$employee->id,
            'team_role' => 'required',
            // 'team_admin_employee_id' => 'required_if:team_role,==,0',
            'active' => 'nullable',
        ]);
        if ($request->team_role == 0 && !$request->team_admin_employee_id) {
            return redirect()->back()->with('error','An Employee must need to select Team head');
        }

        $user->username = $request->employee_id;
        $user->name = $request->name;
        $user->username = $request->employee_id;
        if ($request->employee_password) {
            $user->password = Hash::make($request->employee_password);
            $user->temp_password = $request->employee_password;
        }
        $user->office_location_id = $request->head_office;
        $user->track = $request->track_him ? 1 : 0;
        $user->attendance = $request->attendance ? 1 : 0;
        if ($request->hasFile('avater')) {
            $f = 'users/' . $user->avater;
            if (Storage::disk('upload')->exists($f)) {
                Storage::disk('upload')->delete($f);
            }
            $cp = $request->file('avater');
            $extension = strtolower($cp->getClientOriginalExtension());
            $randomFileName = $user->username . '_company_' . date('his') . '_' . rand(100, 99) . '.' . $extension;
            Storage::disk('public')->put('users/' . $randomFileName, File::get($cp));
            $user->avater =  $randomFileName;
        }
        $user->save();

        $employee->user_id = $user->id;
        $employee->employee_id  = $user->username;
        $employee->name = $request->name;
        $employee->rfid = $request->rfid;
        $employee->joining_date = $request->joining_date;
        $employee->designation_id = $request->designation;
        $employee->mobile = $request->mobile;
        $employee->email = $request->email;
        $employee->department_id = $request->department;
        $employee->company_id = $request->company;
        $employee->team_admin  = $request->team_role;
        $employee->team_admin_id  = $request->team_admin_employee_id;
        $employee->active  = $request->active ? 1 : 0;
        $employee->addedBy_id  = Auth::id();
        $employee->save();

        if (!$employee->team_admin) {
            Employee::where('team_admin_id',$employee->id)->update(['team_admin_id'=>null]);
        }

        if ($employee->team_admin) {
            $employee->team_admin_id= null;
            $employee->save();
        }

        return redirect()->back()->with('success', 'Employe Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = Employee::find($id);
        $employee->delete();
        return redirect()->back()->with('success', 'Employe Deleted Successfully');
    }

    public function employeeAttaendance(Employee $employee)
    {
        $attendances = Attendance::where('user_id', $employee->user->id)->latest()->paginate(30);
        return view('admin.employee.attendance', compact('employee', 'attendances'));
    }

    public function employeeLocation(Request $request, Employee $employee)
    {
        $locations = UserLocation::with('Office')
        ->where('user_id', $employee->user->id)
        ->where(function($q){
            $q->where('office_location_id', "<>", null);
            $q->orWhere('Location','!=',null);
        })
        ->latest()
        ->paginate(100);
        return view('admin.employee.location', compact('locations', 'employee'));
    }

    public function employeeOfficeVisits(Request $request, Employee $employee)
    {
        $office_visit_dates = UserLocation::where('user_id', $employee->user_id)->where('office_location_id', '!=', null)->groupBy('date')->select('id', 'date')->latest()->paginate(30);
        return view('admin.employee.officeVisits', compact('office_visit_dates', 'employee'));
    }
    public function employeeOfficeVisitsDetails(Request $request, Employee $employee)
    {
        // $office_visit_locations = UserLocation::where('user_id', $employee->user_id)
        //     ->where('date', $request->date)
        //     ->where('office_location_id', '!=', null)
        //     ->groupBy('office_location_id')
        //     ->orderBy('created_at')
        //     ->get();

        $office_visit_locations = UserLocation::where('user_id', $employee->user_id)
            ->where('date', $request->date)
            ->where('office_location_id', '!=', null)
            ->orderBy('created_at')
            ->get();

        return view('admin.employee.officeVisitsList', compact('office_visit_locations', 'employee'));
    }

    public function employeeCustomers(Request $request, Employee $employee)
    {
        // $customers = Customer::where('employee_id', $employee->id)->where('active', true)->latest()->paginate(30);
        $customers = $employee->customers()->where('active', true)->latest()->paginate(30);
        $q = '';
        if ($request->ajax()) {
            return view('admin.customer.ajax.customersAjax', compact('q', 'customers', 'employee'))->render();
        }
        return view('admin.employee.customers.index', compact('customers', 'q', 'employee'));
    }

    public function employeeCustomersAdd(Request $request, Employee $employee)
    {
        $divisions = DB::table('divisions')->select(['id', 'name'])->orderBy('name')->get();
        $districts = DB::table('districts')->select(['id', 'name', 'division_id'])->orderBy('name')->get();
        $thanas = DB::table('upazilas')->select(['id', 'name', 'district_id', 'division_id'])->orderBy('name')->get();
        $companies = CustomerCompany::where('active', true)->get();
        return view('admin.employee.customers.create', compact('companies', 'divisions', 'districts', 'thanas', 'employee'));
    }
    public function employeeCustomersStore(Request $request, Employee $employee)
    {

        $request->validate([
            'email' => 'required|email|unique:customers,email',
            'company_name' => 'required',
            'customer_code' => 'nullable',
            'customer_name' => 'required',
            'client_address' => 'nullable',
            'division' => 'nullable',
            'district' => 'nullable',
            'thana' => 'nullable',
            'phone' => 'required',
            'area_id' => 'nullable',
            'contact_person_name' => 'nullable',
            'designation' => 'nullable',
            'mobile' => 'nullable',
            'ledger_balance' => 'nullable',
            'active' => 'nullable',
        ]);
        $user = new User();
        $user->name = $request->customer_name;
        $user->username = $request->email;
        $randPas = rand(10000, 555555);
        $user->password = Hash::make($randPas);
        $user->temp_password = $randPas;
        $user->track = true;
        $user->save();

        $customer = new Customer;
        $customer->user_id = $user->id;
        $customer->employee_id = $employee->id;
        $customer->company_id = $request->company_name;
        $customer->employee_name = $employee->name;
        $customer->customer_code = $request->customer_code;
        $customer->customer_name = $request->customer_name;
        $customer->client_address = $request->client_address;
        $customer->phone = $request->phone;
        $customer->area = $request->area;
        $customer->customer_type = $request->customer_type;
        $customer->contact_person_name = $request->contact_person_name;
        $customer->designation = $request->designation;
        $customer->email = $request->email;
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
        return back()->with('success', 'New Customer Added Successfully Created.');
    }
    public function employeeCustomersEdit(Request $request, Employee $employee, Customer $customer)
    {
        $companies = CustomerCompany::where('active', true)->get();
        $divisions = DB::table('divisions')->select(['id', 'name'])->orderBy('name')->get();
        $districts = DB::table('districts')->select(['id', 'name', 'division_id'])->orderBy('name')->get();
        $thanas = DB::table('upazilas')->select(['id', 'name', 'district_id', 'division_id'])->orderBy('name')->get();
        $div = Division::where('name', 'like', '%' . $customer->division . '%')->first();
        $dis = District::where('name', 'like', '%' . $customer->district . '%')->first();
        $selectedDistricts = District::where('division_id', $div->id)->get();
        $selectedThanas = Upazila::where('district_id', $dis->id)->get();
        return view('admin.employee.customers.edit', compact('customer', 'companies', 'divisions', 'districts', 'thanas', 'selectedDistricts', 'selectedThanas', 'employee'));
    }
    public function employeeCustomersUpdate(Request $request, Employee $employee, Customer $customer)
    {
        $user = $customer->user;
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'company_name' => 'required',
            'customer_code' => 'nullable',
            'customer_name' => 'required',
            'client_address' => 'nullable',
            'division' => 'nullable',
            'district' => 'nullable',
            'thana' => 'nullable',
            'phone' => 'required',
            'area_id' => 'nullable',
            'contact_person_name' => 'nullable',
            'designation' => 'nullable',
            'mobile' => 'nullable',
            'ledger_balance' => 'nullable',
            'active' => 'nullable',
        ]);

        if ($request->email != $customer->user->username) {
            $user->name = $request->customer_name;
            $user->username = $request->email;
            $user->track = true;
            $user->save();
        }

        $customer->user_id = $user->id;
        $customer->employee_name = $employee->name;
        $customer->customer_code = $request->customer_code;
        $customer->customer_name = $request->customer_name;
        $customer->client_address = $request->client_address;
        $customer->phone = $request->phone;
        $customer->customer_type = $request->customer_type;
        $customer->contact_person_name = $request->contact_person_name;
        $customer->designation = $request->designation;
        $customer->email = $request->email;
        $customer->mobile = $request->mobile;
        $customer->ledger_balance = $request->ledger_balance ?? 0.00;
        $customer->active = $request->active ? 1 : 0;
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
        return back()->with('success', ' Customer Account Updated Successfully Updated.');
    }
    public function employeeCustomersDelete(Request $request, Employee $employee, Customer $customer)
    {
        $customer->delete();
        return redirect()->back()->with('success', 'Customer Successfully Deleted');
    }

    public function employeeCustomersSearch(Request $request, Employee $employee)
    {
        $q = $request->q;
        $customers = Customer::where('customer_name', 'like', '%' . $q . '%')
            ->where(function ($qq) use ($q) {
                $qq->orWhere('company_name', 'like', '%' . $q . '%');
                $qq->orWhereHas('company', function ($query) use ($q) {
                    $query->where('name', 'like', '%' . $q . '%');
                    $query->where('active', true);
                });
                $qq->orWhere('email', 'like', '%' . $q . '%');
                $qq->orWhere('mobile', 'like', '%' . $q . '%');
                $qq->orWhere('customer_code', 'like', '%' . $q . '%');
                $qq->orWhere('phone', 'like', '%' . $q . '%');
            })
            ->where('employee_id', $employee->id)
            ->with('user')
            ->latest()
            ->paginate(30);
        return view('admin.customer.ajax.customersAjax', compact('q', 'customers', 'employee'))->render();
    }
    public function teamAdminListAjax(Request $request)
    {

       $team_admins = Employee::where('company_id',$request->company)->where('team_admin',true)->get();
       $employee = Employee::find($request->employee);
       if (count($team_admins) > 0) {
           return response()->json([
               'success'=>true,
               'html'=>view('admin.employee.ajax.employee_admin_list',compact('team_admins','employee'))->render(),
           ]);
       }else{
        return response()->json([
            'success'=>false,
        ]);
       }

    }
    public function convayancesBillHistory(Request $request, Employee $employee)
    {
        $histories = EmployeePayment::where('employee_id',$employee->id)->latest()->paginate(50);
        return view('admin.employee.convayancesBillHistory',compact('employee','histories'));
    }
}
