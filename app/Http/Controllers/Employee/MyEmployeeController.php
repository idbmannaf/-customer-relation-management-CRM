<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Call;
use App\Models\CallReferencePevot;
use App\Models\CallToSendProductRequest;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\OfficeLocation;
use App\Models\Product;
use App\Models\Team;
use App\Models\User;
use App\Models\UserLocation;
use App\Models\Visit;
use App\Models\VisitPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MyEmployeeController extends Controller
{
    use EmployeeTrait;
    public function myTeam(Request $request)
    {
        menuSubmenu('team', 'myTeam');
        $user = Auth::user();
        $employee = $user->employee;
        if (!$employee) {
            abort(403);
        }
        $team_members = Employee::where('team_admin_id', $employee->id)->where('team_admin', false)->paginate(30);
        return view('employee.myEmployee.myTeam', compact('employee', 'team_members'));
    }

    public function myTeamAdd(Request $request)
    {
        $employee = Auth::user()->employee;
        if (!$employee) {
            abort(403);
        }
        menuSubmenu('team', 'myTeamAdd');
        if ($request->isMethod('get')) {
            $companies = Company::where('active', true)->get();
            $designations = Designation::latest()->get();
            $departments = Department::latest()->get();
            $office_locations = OfficeLocation::where('active', true)->get();
            return view('employee.myEmployee.createEmployee', compact('companies', 'designations', 'departments', 'office_locations',));
        }

        //Employee Store
        if ($request->isMethod('post')) {
            $request->validate([
                'name' => 'required',
                'employee_id' => 'required|unique:employees,employee_id,',
                'employee_password' => 'required',
                'joining_date' => 'required|date',
                'designation' => 'required',
                'department' => 'required',
                'company' => 'required',
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
                }

                $user->save();
            }

            $employee = new Employee;
            $employee->user_id = $user->id;
            $employee->employee_id  = $user->username;
            $employee->name = $user->name;
            $employee->joining_date = $request->joining_date;
            $employee->designation_id = $request->designation;
            $employee->mobile = $request->mobile;
            $employee->email = $request->email;
            $employee->department_id = $request->department;
            $employee->company_id = $request->company;
            $employee->team_admin  = 0;
            $employee->team_admin_id  = Auth::user()->employee->id;
            $employee->active  = $request->active ? 1 : 0;
            $employee->addedBy_id  = Auth::id();
            $employee->save();
            return redirect()->back()->with('success', 'Employe Added Successfully');
        }
    }

    public function myTeamEdit(Request $request, Employee $employee)
    {
        $employeeAdmin = Auth::user()->employee;
        if (!$employeeAdmin) {
            abort(403);
        }

        // Edit
        if ($request->isMethod('get')) {
            $companies = Company::where('active', true)->get();
            $designations = Designation::latest()->get();
            $departments = Department::latest()->get();

            return view('employee.myEmployee.editEmployee', compact('employee', 'companies', 'designations', 'departments'));
        }

        //Employee Update
        if ($request->isMethod('patch')) {

            $user = $employee->user;
            $request->validate([
                'name' => 'required',
                'employee_id' => 'required|unique:users,username,' . $user->id,
                'joining_date' => 'required|date',
                'designation' => 'required',
                'department' => 'required',
                'active' => 'nullable',
            ]);

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
            $employee->joining_date = $request->joining_date;
            $employee->designation_id = $request->designation;
            $employee->mobile = $request->mobile;
            $employee->email = $request->email;
            $employee->department_id = $request->department;
            // $employee->company_id = $request->company;
            $employee->team_admin  = 0;
            $employee->active  = $request->active ? 1 : 0;
            $employee->editedBy_id  = Auth::id();
            $employee->save();

            return redirect()->back()->with('success', 'Employe Updated Successfully');
        }
    }
    public function myEmployeeLocation(Employee $employee)
    {
        menuSubmenu('myLocationHistory', 'myLocationHistory');
        if ($employee->user_id) {
            $myEmployeelocations = UserLocation::where('user_id', $employee->user_id)->latest()->paginate(100);
            return view('employee.myEmployee.employeeLocation', [
                'employee' => $employee,
                'locations' => $myEmployeelocations
            ]);
        } else {
            return back();
        }
    }
    public function myEmployeeAttandace(Employee $employee)
    {
        menuSubmenu('myAttandance', 'myAttandance');
        if ($employee->user_id) {
            $attendance = Attendance::where('user_id', $employee->user_id)->latest()->paginate(100);
            return view('employee.myEmployee.employeeAttendance', [
                'attendances' => $attendance,
                'employee' => $employee
            ]);
        } else {
            return back();
        }
    }

    public function myEmployeeCustomers(Request $request, Employee $employee)
    {
        $customers = Customer::where('employee_id', $employee->id)->where('active', true)->latest()->paginate(50);
        return view('employee.myEmployee.myEmployeeCustomers', [
            'customers' => $customers,
            'employee' => $employee,
        ]);
    }

    public function myEmployeeOfficeVisit(Employee $employee)
    {
        $office_visit_dates = UserLocation::where('user_id', $employee->user_id)->where('office_location_id', '!=', null)->groupBy('date')->select('id', 'date')->latest()->paginate(30);
        return view('employee.myEmployee.officeVisits', compact('office_visit_dates', 'employee'));
    }
    public function myEmployeeOfficeVisitByDate(Request $request, Employee $employee)
    {
        $office_visit_locations = UserLocation::where('user_id', $employee->user_id)->where('date', $request->date)->where('office_location_id', '!=', null)->groupBy('office_location_id')->orderBy('created_at')->paginate(30);

        return view('employee.myEmployee.officeVisitsList', compact('office_visit_locations', 'employee'));
    }
    public function myEmployeeVisitSale(Request $request, Employee $employee)
    {
        menuSubmenu('sales', 'allSales');
        $input = $request->all();
        if ($request->has('time') || $request->has('employee')) {
            $visit_sales = Visit::where(function ($query) use ($request) {
                if ($request->time == 'today') {
                    $query->whereDate('date_time', now());
                } elseif ($request->time == 'yesterday') {
                    $query->whereDate('date_time', now()->subDays(1));
                } elseif ($request->time == 'last_7_days') {
                    $query->where('date_time', '>=', now()->subDays(7));
                } elseif ($request->time == 'last_month') {
                    $query->where('date_time', '>=', now()->subDays(30));
                }
                if ($request->employee) {
                    $query->where('employee_id', $request->employee);
                }
            })
                ->where('sale_amount', '!=', 0.00)
                ->orderBy('date_time')->paginate(50);
        } else {
            $visit_sales = Visit::where('sale_amount', '!=', 0.00)
                ->orderBy('date_time')->paginate(50);
        }


        $my_employees = Employee::whereIn('id', $this->alEmployees())->get();
        return view('employee.myEmployee.sale.visitSale', compact('employee', 'visit_sales', 'input', 'my_employees'));
    }
    public function myEmployeeVisitCollection(Request $request, Employee $employee)
    {
        menuSubmenu('collections', 'allCollections');
        $input = $request->all();
        if ($request->has('time') || $request->has('employee')) {
            $visit_collection = Visit::where(function ($query) use ($request) {
                if ($request->time == 'today') {
                    $query->whereDate('date_time', now());
                } elseif ($request->time == 'yesterday') {
                    $query->whereDate('date_time', now()->subDays(1));
                } elseif ($request->time == 'last_7_days') {
                    $query->where('date_time', '>=', now()->subDays(7));
                } elseif ($request->time == 'last_month') {
                    $query->where('date_time', '>=', now()->subDays(30));
                }
                if ($request->employee) {
                    $query->where('employee_id', $request->employee);
                }
            })
                ->where('collection_amount', '!=', 0.00)
                ->orderBy('date_time')->paginate(50);
        } else {
            $visit_collection = Visit::where('sale_amount', '!=', 0.00)
                ->orderBy('date_time')->paginate(50);
        }

        $my_employees = Employee::whereIn('id', $this->alEmployees())->get();
        return view('employee.myEmployee.sale.visitCollection', compact('employee', 'visit_collection', 'my_employees', 'input'));
    }
    public function myEmployeeAttandance(Request $request)
    {

        $myEmployeesUserIds = $this->alEmployeesUserIds();
        menuSubmenu('attendance', 'allAttendance');
        $attendances = Attendance::whereIn('user_id', $myEmployeesUserIds)->with('company')->whereDate('date', Carbon::now()->format('Y-m-d'))
            ->paginate(100);
        return view('employee.attendance.index', compact('attendances'));
    }

    public function myEmployeeAttendanceHistory(Request $request)
    {
        $myEmployeesUserIds = $this->alEmployeesUserIds();
        menuSubmenu('attendance', 'attendanceHistory');
        $attendances = Attendance::whereIn('user_id', $myEmployeesUserIds)->with('company')->orderBy('date', 'DESC')->paginate(50);

        return view('employee.attendance.attandanceHistory', compact('attendances'));
    }
    public function myEmployeeAttendanceReport(Request $request)
    {
        menuSubmenu('attendance', 'attendanceReport');

        if ($request->type == 'filter') {

            $attendances = Attendance::where(function ($query) use ($request) {
                if ($request->s_date) {
                    $query->where('created_at', '>', $request->s_date);
                    // $query->where('created_at', '<=', now())->where('created_at', '>=', now()->subDays(1));
                } elseif ($request->e_date) {
                    $query->where('created_at', '<', $request->s_date);
                    // $query->where('created_at', '<=', now())->where('created_at', '>=', now()->subDays(1));
                } elseif ($request->s_date && $request->e_date) {
                    $query->where('created_at', '<=', now()->parse($request->s_date))->where('created_at', '>=', now()->parse($request->e_date));
                }
                if ($request->employee) {
                    $employee = Employee::where('id', $request->employee)->first();
                    // dd( $employee->user_id);
                    $query->where('user_id', $employee->user_id);
                }
            })->latest()->paginate(100);
            if ($request->employee) {
                $employee = Employee::find($request->employee);
                return view('employee.attendance.attandanceReport', [
                    'type' => $request->type,
                    'employee' => $employee,
                    'attendances' => $attendances,
                    'input' => $request->all(),
                ]);
            } else {
                return view('employee.attendance.attandanceReport', [
                    'type' => $request->type,
                    'attendances' => $attendances,
                    'input' => $request->all(),
                ]);
            }
        }
        return view('employee.attendance.attandanceReport', [
            'input' => $request->all(),
        ]);
    }
    public function setAttendance(Request $request, User $user)
    {

        $team_ids = $user->team_roles->pluck('team_id');
        $teams = Team::whereIn('id', $team_ids)->groupBy('company_id')->get();
        foreach ($teams as $team) {
            $radius = .2;
            $company_lat = $team->company->lat ?? 23.7614067;
            $company_lng = $team->company->lng ?? 90.4195412;
            if ($company_lat and $company_lng) {
                $haversine = "(6371 * acos(cos(radians(" . $company_lat . "))
                                * cos(radians(`lat`))
                                * cos(radians(`lng`)
                                - radians(" . $company_lng . "))
                                + sin(radians(" . $company_lat . "))
                                * sin(radians(`lat`))))";
            }

            $user_lcation = UserLocation::select('id', 'lat', 'lng')
                ->where('id', $user->id)
                ->whereRaw("{$haversine} < ?", [$radius])
                ->selectRaw("{$haversine} AS distance")
                ->latest()
                ->orderBy('distance')
                ->first();

            if ($user_lcation) {
                $attendance = Attendance::where('user_id', $user->id)
                    // ->where('company_id', $team->company_id)
                    ->whereDate('date', Carbon::now()->format('Y-m-d'))
                    ->first();
                if ($attendance) {
                    // $attendance->company_id = $team->company_id;
                    $attendance->lat = $user_lcation->lat;
                    $attendance->lng = $user_lcation->lng;
                    $attendance->logged_out_at = now();
                    $attendance->save();
                } else {
                    $attendance = new Attendance;
                    $attendance->user_id = $user->id;
                    $attendance->date = date('Y-m-d');
                    $attendance->company_id = $team->company_id;
                    $attendance->lat = $user_lcation->lat;
                    $attendance->lng = $user_lcation->lng;
                    $attendance->logged_in_at = now();
                    $attendance->logged_out_at = now();
                    $attendance->save();
                }
                return response()->json([
                    'success' => true,
                ]);
                break;
            }
            return response()->json([
                'success' => false,
            ]);
        }
    }

    public function calls(Request $request)
    {

        $employee = Auth::user()->employee;
        $type = $request->type;
        menuSubmenu('serviceColls', $type . '_serviceColls');
        $customer_ids =  Customer::whereIn('employee_id', $this->alEmployees())
            ->pluck('id')
            ->toArray();
        if ($request->call) {
            $calls = Call::where('id', $request->call)->paginate(10);
        } else {
            if ($employee->company->access_all_call && $employee->team_admin) {
                if ($type == 'pending') {
                    $calls = Call::where('approved_at', null)
                        ->latest()
                        ->paginate(100);
                } elseif ($type == 'approved') {
                    $calls = Call::where('approved_at', '!=', null)
                        ->latest()
                        ->paginate(100);
                } elseif ($type == 'done') {
                    $calls = Call::where('done_at', '!=', null)
                        ->latest()
                        ->paginate(100);
                } else {
                    $calls = Call::latest()
                        ->paginate(100);
                }
            } else {
                if ($type == 'pending') {
                    $calls = Call::where(function ($q) use ($customer_ids) {
                        $q->whereIn('customer_id', $customer_ids);
                        $q->orWhereIn('employee_id', $this->alEmployees());
                    })
                        ->where('approved_at', null)
                        ->latest()
                        ->paginate(100);
                } elseif ($type == 'approved') {
                    $calls = Call::where(function ($q) use ($customer_ids) {
                        $q->whereIn('customer_id', $customer_ids);
                        $q->orWhereIn('employee_id', $this->alEmployees());
                    })->where('approved_at', '!=', null)
                        ->where('done_at', null)
                        ->latest()
                        ->paginate(100);
                } elseif ($type == 'done') {
                    $calls = Call::where(function ($q) use ($customer_ids) {
                        $q->whereIn('customer_id', $customer_ids);
                        $q->orWhereIn('employee_id', $this->alEmployees());
                    })->where('approved_at', '!=', null)
                        ->where('done_at', '!=', null)
                        ->latest()
                        ->paginate(100);
                } else {
                    $calls = Call::where(function ($q) use ($customer_ids) {
                        $q->whereIn('customer_id', $customer_ids);
                        $q->orWhereIn('employee_id', $this->alEmployees());
                    })->latest()
                        ->paginate(100);
                }
            }
        }

        return view('employee.calls.calls', compact('calls', 'employee', 'type'));
    }
    public function referanceCall(Request $request)
    {
        $employee = Auth::user()->employee;
        if (!$employee->team_admin) {
            abort(403, 'Permission Denied');
        }
        menuSubmenu('serviceColls', 'referanceCall');
        $calls =  $employee->refferCalls()->orderBy('id', 'DESC')->paginate();
        return view('employee.calls.refferCalls', compact('calls', 'employee'));
    }
    public function addCalls(Request $request)
    {

        $employee = Auth::user()->employee;
        if ($request->isMethod('get')) {
            $customers =  Customer::whereIn('employee_id', $this->alEmployees())->get();
            $myEmployees = Employee::whereIn('id', $this->alEmployees())->get();
            // foreach ($myEmployees as $key => $e) {
            //    dump($e->company_id);
            // }
            // die;
            // dd($myEmployees);
            $team_admins = Employee::where('team_admin', true)->where('id', '!=', $employee->id)->with('company')->orderBy('company_id')->get();
            return view('employee.calls.addCalls', compact('customers', 'employee', 'myEmployees', 'team_admins'));
        }

        if ($request->isMethod('post')) {
            $request->validate([
                'date' => 'required|date',
                'time' => 'required',
                'customer' => 'required',
                'type' => 'required',
                'purpose_of_visit' => 'nullable',
                'customer_address' => 'required',
                'service_address' => 'nullable',
            ]);
            // dd($request->all());
            $date_time = $request->date . " " . $request->time . ":00";
            $call = new Call;
            $call->date_time = $date_time;
            $call->type = $request->type;
            $call->customer_id = $request->customer;
            $call->service_address = $request->service_address;

            if ($employee->team_admin) {
                $call->employee_id = $request->employee;
            } else {
                $call->employee_id = $employee->id;
            }

            // $call->customer_location_id = $request->customer_location_id;

            if (is_numeric($request->customer_address)) {
                $call->customer_address = OfficeLocation::find($request->customer_address)->title;
            } else {
                $call->customer_address = $request->customer_address;
            }

            $call->inhouse_product = $request->inhouse_product ? 1 : 0;
            $call->purpose_of_visit = $request->purpose_of_visit;
            $call->admin_note = $request->admin_note;
            $call->approved_at = $request->approved_at ? now() : null;
            $call->addedBy_id = Auth::id();
            $call->save();
            if ($call->approved_at) {
                $call->approved_by = Auth::id();
                $call->save();
            }
            if ($request->team_admin) {
                foreach ($request->team_admin as $team_admin_id) {
                    $referance_pevot = new CallReferencePevot;
                    $referance_pevot->call_id = $call->id;
                    $referance_pevot->team_admin_employee_id = $team_admin_id;
                    $referance_pevot->addedBy_id = Auth::id();
                    $referance_pevot->save();
                }
            }
            return redirect()->back()->with('success', 'call Added Successfully');
        }
    }
    public function updateCalls(Request $request, Call $call)
    {
        $employee = Auth::user()->employee;
        if ($request->isMethod('get')) {
            $customers =  Customer::whereIn('employee_id', $this->alEmployees())->get();
            $customer_office = $call->customer->company->offices;
            $myEmployees = Employee::whereIn('id', $this->alEmployees())->get();
            $team_admins = Employee::where('team_admin', true)->where('id', '!=', $employee->id)->with('company')->orderBy('company_id')->get();
            return view('employee.calls.editCalls', compact('call', 'employee', 'customers', 'team_admins', 'customer_office', 'myEmployees'));
        }
        if ($request->isMethod('post')) {

            if ($call->approved_at) {
                return redirect()->back()->with('warning', 'This Call Already Approved. You are not able to Edit');
            }
            $request->validate([
                'date' => 'required|date',
                'time' => 'required',
                'customer' => 'required',
                'type' => 'required',
                'purpose_of_visit' => 'nullable',
                'customer_address' => 'required',
                'service_address' => 'nullable',
            ]);
            $date_time = $request->date . " " . $request->time . ":00";
            $call->date_time = $date_time;
            $call->customer_id = $request->customer;
            $call->service_address = $request->service_address;
            if (is_numeric($request->customer_address)) {
                $call->customer_address = OfficeLocation::find($request->customer_address)->title;
            } else {
                $call->customer_address = $request->customer_address;
            }

            $call->inhouse_product = $request->inhouse_product ? 1 : 0;
            $call->employee_id = $request->employee;
            $call->purpose_of_visit = $request->purpose_of_visit;
            $call->admin_note = $request->admin_note;
            $call->approved_at = $request->approved_at ? now() : null;
            $call->editedBy_id = Auth::id();
            $call->save();
            if ($call->approved_at) {
                $call->approved_by = Auth::id();
                $call->save();
            }
            if ($request->team_admin) {
                $call->refferTeamHeads()->detach();
                foreach ($request->team_admin as $team_admin_id) {
                    $referance_pevot = new CallReferencePevot;
                    $referance_pevot->call_id = $call->id;
                    $referance_pevot->team_admin_employee_id = $team_admin_id;
                    $referance_pevot->addedBy_id = Auth::id();
                    $referance_pevot->save();
                }
            }
            return redirect()->back()->with('success', 'Call Updated Successfully');
        }
    }
    public function callWiseVisitPlan(Request $request, Call $call)
    {

        $employee = Auth::user()->employee;

        $visit_plans = VisitPlan::where('call_id', $call->id)->latest()->get();
        $addeByArray = $this->addedBy();

        return view('employee.calls.vistiPlan.callWiseVistiPlan', compact('call', 'employee', 'visit_plans', 'addeByArray'));
    }

    public function addVisitPlan(Request $request, Call $call)
    {

        $employee = Auth::user()->employee;
        if (!$call || !$employee->team_admin || !$employee->company->access_all_call) {
            abort(403, 'Permission Denied!. You\'are not able to add Visit plan in this call');
        }
        if (!$employee->team_admin) {
            abort(403, 'Permission Denied');
        }

        if ($request->isMethod('get')) {
            $customer =  $call->customer;
            $my_employees = Employee::whereIn('id', $this->alEmployees())->get();
            $customer_office_loations = $call->customer->company->offices;
            return view('employee.calls.vistiPlan.createCallWiseVistiPlan', compact('call', 'employee', 'customer', 'my_employees', 'customer_office_loations'));
        }
        if ($request->isMethod('post')) {
            $request->validate([
                'date' => 'required|date',
                'time' => 'required',
                'employee' => 'required',
                'purpose_of_visit' => 'nullable|string',
                'customer_address' => 'required',
                'service_type' => 'required',
            ]);
            if ($request->service_type == 'service') {
                $request->validate([
                    'service_address' => 'required'
                ]);
            }
            if ($request->employee) {
                $employee = Employee::find($request->employee);
            }
            $date_time = $request->date . " " . $request->time . ":00";
            $vistPlan = new VisitPlan;
            $vistPlan->service_type = $request->service_type;
            $vistPlan->call_id = $call->id;
            $vistPlan->type = $request->visit_type ?? 'weekly';
            $vistPlan->employee_id = $employee->id;
            $vistPlan->customer_id = $call->customer_id;
            $vistPlan->service_address = $request->service_address;
            $vistPlan->date_time = $date_time;
            $vistPlan->purpose_of_visit = $request->purpose_of_visit;
            $vistPlan->payment_collection_date = $request->payment_collection_date ?? null;
            $vistPlan->payment_maturity_date = $request->payment_maturity_date ?? null;

            if (is_numeric($request->customer_address)) {
                $vistPlan->customer_address = OfficeLocation::find($request->customer_address)->title;
            } else {
                $vistPlan->customer_address = $request->customer_address;
            }
            // $vistPlan->customer_address = $request->customer_address;
            // $vistPlan->customer_office_location_id = $request->customer_office_location;
            $vistPlan->addedBy_id = Auth::id();
            if ($request->approved) {
                $vistPlan->status = 'approved';
                $vistPlan->team_admin_approved_at = now();
            } else {
                $vistPlan->status = 'pending';
            }
            $vistPlan->save();
            return redirect()->back()->with('success', "Visit Plan Created Successfuly");
        }
    }
    public function emplyeeDetailsAboutMovement(Request $request, Visit $visit)
    {

        $type = $request->type;
        $employee = $visit->employee;
        if ($type == 'location') {
            $locations = UserLocation::with('Office')
                ->where('user_id', $employee->user->id)
                ->where('office_location_id', "<>", null)
                ->whereDate('date', Carbon::parse($visit->date_time)->format('Y-m-d'))
                ->latest()
                ->paginate(100);
            // view('employee.visitPlan.visit.convayances.location')
            // view('employee.visitPlan.visit.convayances.officeVisitsList')
            return view('employee.visitPlan.visit.convayances.location', compact('locations', 'employee', 'visit'));
        } elseif ($type == 'visit') {

            $office_visit_locations = UserLocation::where('user_id', $employee->user_id)
                ->whereDate('date', Carbon::parse($visit->date_time)->format('Y-m-d'))
                ->where('office_location_id', '!=', null)
                ->orderBy('created_at')
                ->get();

            return view('employee.visitPlan.visit.convayances.officeVisitsList', compact('office_visit_locations', 'employee', 'visit'));
        } else {
            return back();
        }
    }

    public function sendRequestToTheCustomer(Request $request, Call $call)
    {
        return view('employee.calls.request_to_the_customer', compact('call'));
    }
    public function addProductToSendRequestToTheCustomerAjax(Request $request)
    {
        $call = Call::find($request->call);
        $product = Product::find($request->product_id);
        $quantity = $request->quantity;

        if ($call->sent_product()) {
            return response()->json([
                'success' => false,
                'message' => 'You Are Not Abole To more Product'
            ]);
        }
        $item = new CallToSendProductRequest;
        $item->call_id = $call->id;
        $item->customer_id = $call->customer_id;
        $item->quantity = $quantity;
        $item->product_id = $product->id;
        $item->status = 'temp';
        $item->addedBy_id = Auth::id();
        $item->save();
        return response()->json([
            'success' => true,
            'html' => view('employee.calls.request_to_the_customer_ajax', compact('item', 'call'))->render()
        ]);
    }
    public function deleteProductToSendRequestToTheCustomerAjax(Request $request)
    {
        $item = CallToSendProductRequest::find($request->item);
        if ($request->type == 'quantity') {
            if ($item->sent) {
                return response()->json([
                    'success' => false,
                    'message' => "You are not able to delete this product. Because The customer already sent this product"
                ]);
            }
            $item->quantity = $request->value;
            $item->save();
            return response()->json([
                'success' => true,
            ]);
        }
        if ($request->type == 'delete') {
            $item = CallToSendProductRequest::find($request->item);
            return response()->json([
                'success' => false,
                'message' => "You are not able to change this product quantity. Because The customer already sent this product"
            ]);
            $item->delete();
            return response()->json([
                'success' => true,
            ]);
        }
    }
    public function sendRequestToTheCustomerPost(Request $request, Call $call)
    {
        $call->call_products()->update([
            'submited' => true
        ]);
        return back()->with('success', 'Product Send To The Customer Successfully');
    }
    public function receivedCustomerRequestProduct(Request $request)
    {
        $type = $request->type;
        menuSubmenu('receivedCustomerRequestProduct', 'receivedCustomerRequestProduct_', $type);
        if ($type == 'unsent') {
            $products = CallToSendProductRequest::where('submited', true)->where('sent', false)->whereHas('call', function ($q) {
                $q->where('done_at',  null);
            })->latest()->paginate(100);
        } elseif ($type == 'received') {
            $products = CallToSendProductRequest::where('submited', true)->where('received', true)->whereHas('call', function ($q) {
                $q->where('done_at',  null);
            })->latest()->paginate(100);
        } elseif ($type == 'not_received') {
            $products = CallToSendProductRequest::where('submited', true)->where('sent', true)->where('received', false)->whereHas('call', function ($q) {
                $q->where('done_at',  null);
            })->latest()->paginate(100);
        } elseif ($type == 'ready_for_delivered') {
            $products = CallToSendProductRequest::where('received', true)->where('delivered', false)->whereHas('call', function ($q) {
                $q->where('done_at', "!=", null);
            })->latest()->paginate(100);
        } elseif ($type == 'delivered') {
            $products = CallToSendProductRequest::where('received', true)->where('delivered', true)->whereHas('call', function ($q) {
                $q->where('done_at', "!=", null);
            })->latest()->paginate(100);
        } elseif ($type == 'customer_received') {
            $products = CallToSendProductRequest::where('delivered', true)->where('customer_received', true)->whereHas('call', function ($q) {
                $q->where('done_at', "!=", null);
            })->latest()->paginate(100);
        }
        return view('employee.calls.customer_product_sent', compact('products', 'type'));
    }
    public function receivedCustomerRequestProductitem(Request $request, CallToSendProductRequest $item)
    {
        $status = $request->status;
        if ($status == 'received') {
            $item->received = true;
            $item->received_by = Auth::id();
            $item->received_at = now();
            $item->save();
        }
        if ($status == 'delivered') {
            $item->delivered = true;
            $item->delivered_by = Auth::id();
            $item->delivered_at = now();
            $item->save();
        }

        return redirect()->back()->with('success', "Product Successfully {$status}");
    }
}
