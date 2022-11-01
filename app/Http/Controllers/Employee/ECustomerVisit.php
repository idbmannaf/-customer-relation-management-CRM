<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Customer;
use App\Models\CustomerCompany;
use App\Models\CustomerVisit;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\OfficeLocation;
use App\Models\Visit;
use App\Models\VisitPlan;
use Auth;
use Illuminate\Http\Request;

class ECustomerVisit extends Controller
{
    use EmployeeTrait;
    public function index(Request $request)
    {

        $type = $request->type;
        $employee = Auth::user()->employee;

        menuSubmenu('visitPlan', "visitPlan_".$type);

        $employee_ids = $this->alEmployees();
        $addeByArray = $this->addedBy();
        $status = $request->status;
        if ($employee->team_admin) {
            if ($employee->company->access_all_call && $employee->company->access_all_call_visit_plan_without_call) {
                $visit_plans = VisitPlan::orderBy('date_time', 'DESC')->where('status',$status)->paginate(100);
            } elseif ($employee->company->access_all_call) {
                $visit_plans = VisitPlan::where('call_id', '!=', null)->where('status',$status)->orderBy('date_time', 'DESC')->paginate(100);
            } elseif ($employee->company->access_all_call_visit_plan_without_call) {
                $visit_plans = VisitPlan::where('call_id', null)->where('status',$status)->orderBy('date_time', 'DESC')->paginate(100);
            } else {
                $visit_plans = VisitPlan::whereIn('employee_id', $employee_ids)->where('status',$status)->orderBy('date_time', 'DESC')->paginate(100);
            }
        } else {
            $visit_plans = VisitPlan::whereIn('employee_id', $employee_ids)->where('status',$status)->orderBy('date_time', 'DESC')->paginate(100);
        }

        // die;
        return view('employee.visitPlan.visitPlan', compact('type', 'visit_plans', 'employee', 'addeByArray'));
    }
    public function create(Request $request)
    {
        $type = $request->type;
        $employee = Auth::user()->employee;

        $my_employees = Employee::whereIn('id', $this->alEmployees())->get();

        $customers = Customer::whereIn('employee_id', $this->alEmployees())->get();
        // dd($my_employees);

        return view('employee.visitPlan.createVisitPlan', compact('type', 'employee', 'customers', 'my_employees'));
    }
    public function customerAllAjax(Request $request)
    {

        $user = Auth::user();
        $employee = $user->employee;
        if ($employee->team_admin) {
            if ($employee->company->team_head_access_all_customers || $employee->company->team_member_access_all_customers) {
                $customers = Customer::pluck('id')->toArray();
            } else {
                $customers = Customer::whereIn('employee_id', $this->alEmployees())->pluck('id')->toArray();
            }
        } else {
            if ($employee->company->team_member_access_all_customers) {
                $customers = Customer::pluck('id')->toArray();
            } else {
                $customers = Customer::whereIn('employee_id', $this->alEmployees())->pluck('id')->toArray();
            }
        }

        $users = Customer::whereIn('id', $customers)->where(function ($q) use ($request) {
            $q->where('customer_code', 'like', '%' . $request->q . '%');
            $q->orWhere('customer_name', 'like', '%' . $request->q . '%');
        })
            ->select(['id', 'customer_code', 'customer_name'])->take(30)->get();

        if ($users->count()) {
            if ($request->ajax()) {
                // return Response()->json(['items'=>$users]);
                return $users;
            }
        } else {
            if ($request->ajax()) {
                return $users;
            }
        }
    }


    public function selectEmployeeCustomer(Request $request)
    {

        $employee = Employee::find($request->employee);
        if ($employee->team_admin) {
            $team_members = $employee->myTeamMembers()->pluck('id');
        } else {
            $team_members = Customer::where('employee_id', $employee->id)->pluck('id');
        }
        $customer = Customer::where('email', 'like', '%' . $request->q . '%')
            ->orWhere('customer_code', 'like', '%' . $request->q . '%')
            ->orWhere('customer_name', 'like', '%' . $request->q . '%')
            ->whereIn('employee_id', $team_members)
            ->select(['id', 'customer_name', 'customer_code'])
            ->take(30)
            ->get();
        if ($customer->count()) {
            if ($request->ajax()) {
                // return Response()->json(['items'=>$users]);
                return $customer;
            }
        } else {
            if ($request->ajax()) {
                return $customer;
            }
        }
        // ->orWhere('name', 'like', '%'.$request->q.'%')


    }

    public function getCustomerOffice(Request $request)
    {

        $customer = Customer::find($request->customer);
        $customer_company_offices = OfficeLocation::where('customer_company_id', $customer->company_id)->get();


        return response()->json([
            'success' => true,
            'html' => view('employee.visitPlan.ajax.customersCompanyOfficeOption', compact('customer_company_offices', 'customer'))->render()
        ]);

        // if ($customer_company_offices->count()) {
        //     return response()->json([
        //         'success' => true,
        //         'html' => view('employee.visitPlan.ajax.customersCompanyOfficeOption',compact('customer_company_offices','customer'))->render()
        //     ]);
        // } else {
        //     return response()->json([
        //         'success' => false,
        //     ]);
        // }


    }
    public function store(Request $request)
    {

        $employee = Auth::user()->employee;
        if ($request->has('invoice')) {
            $request->validate([
                'date' => 'required|date',
                'time' => 'required',
                'customer' => 'required',
                'payment_collection_date' => 'nullable',
                'payment_maturity_date' => 'nullable',
                'purpose_of_visit' => 'nullable',
                'customer_address' => 'required',
            ]);
            $invoice = Invoice::find($request->invoice);
            if ($invoice->hasVisitPlan()) {
                return back()->with('warning', 'You are not able to assign Visit Plan for collection Because Visit Plan Already Created for Collection');
            }
        } else {
            $request->validate([
                'date' => 'required|date',
                'time' => 'required',
                'customer' => 'required',
                'payment_collection_date' => 'nullable',
                'payment_maturity_date' => 'nullable',
                'purpose_of_visit' => 'nullable',
                'customer_address' => 'required',
                'service_type' => 'required',
            ]);
        }
        if ($request->service_type =='service') {
            $request->validate([
                'service_address' => 'required',
            ]);
        }


        if ($request->employee) {
            $employee = Employee::find($request->employee);
        }

        $date_time = $request->date . " " . $request->time . ":00";
        $vistPlan = new VisitPlan;
        $vistPlan->service_type = $request->service_type;
        $vistPlan->employee_id = $employee->id;
        $vistPlan->customer_id = $request->customer;
        $vistPlan->date_time = $date_time;
        $vistPlan->purpose_of_visit = $request->purpose_of_visit;
        $vistPlan->payment_collection_date = $request->payment_collection_date ?? null;
        $vistPlan->payment_maturity_date = $request->payment_maturity_date ?? null;
        // $vistPlan->customer_office_location_id = $request->customer_office_location;
        //   dd($request->all());
        $vistPlan->service_address = $request->service_address;
        if (is_numeric($request->customer_address)) {
            $vistPlan->customer_address = OfficeLocation::find($request->customer_address)->title;
        } else {
            $vistPlan->customer_address = $request->customer_address;
        }
        $vistPlan->addedBy_id = Auth::id();
        if ($request->approved) {
            $vistPlan->status = 'approved';
            $vistPlan->team_admin_approved_at = now();
        } else {
            $vistPlan->status = 'pending';
        }

        $vistPlan->save();
        if ($request->has('invoice')) {
            $invoice = Invoice::find($request->invoice);
            $vistPlan->invoice_id = $invoice->id;
            $vistPlan->save();
            $invoice->assigned_to = $vistPlan->employee_id;
            $invoice->assigned_by = Auth::id();
            $invoice->assigned_at = now();
            $invoice->collection_status = 'pending';
            $invoice->collection_visit_plan_id = $vistPlan->id;
            $invoice->save();
            return redirect()->back()->with('success', "Visit Plan Created Successfuly For Collection");
        }

        return redirect()->back()->with('success', "{$vistPlan->type} Visit Plan Created Successfuly");
    }
    public function show(CustomerVisit $customerVisit)
    {
        //
    }
    public function edit(Request $request)
    {
        $visit = VisitPlan::find($request->visit);
        $type = $request->type;
        $employee = Auth::user()->employee;

        $my_employees = Employee::whereIn('id', $this->alEmployees())->get();
        if ($visit->employee_id) {
            $customers = Customer::where('employee_id', $visit->employee_id)->get();
        } else {
            $customers = Customer::where('employee_id', $employee->id)->get();
        }
        //If Visit Plan Reffered


        return view('employee.visitPlan.editVisitPlan', compact('type', 'employee', 'customers', 'my_employees', 'visit'));
    }

    public function update(Request $request)
    {

        $vistPlan = VisitPlan::find($request->visit);
        $employee = Auth::user()->employee;
        if ($vistPlan->status == 'approved') {
            return redirect()->back()->with('warning', 'This Visitplan Already Approved. You are not able to update. If you want then Contact the Super Admin');
        }
        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'customer' => 'required',
            'payment_collection_date' => 'nullable',
            'payment_maturity_date' => 'nullable',
            'purpose_of_visit' => 'nullable',
            'customer_address' => 'required',
            'service_address' => 'required',
            'service_type' => 'required',
        ]);
        if ($request->employee) {
            $employee = Employee::find($request->employee);
        }


        $date_time = $request->date . " " . $request->time . ":00";
        $vistPlan->service_type = $request->service_type;
        $vistPlan->employee_id = $employee->id;
        $vistPlan->customer_id = $request->customer;
        $vistPlan->date_time = $date_time;
        $vistPlan->customer_id = $request->customer;
        $vistPlan->service_address = $request->service_address;
        $vistPlan->purpose_of_visit = $request->purpose_of_visit;
        $vistPlan->payment_collection_date = $request->payment_collection_date;
        $vistPlan->payment_maturity_date = $request->payment_maturity_date;
        // $vistPlan->customer_office_location_id = $request->customer_office_location;
        if (is_numeric($request->customer_address)) {
            $vistPlan->customer_address = OfficeLocation::find($request->customer_address)->title;
        } else {
            $vistPlan->customer_address = $request->customer_address;
        }
        $vistPlan->type = $request->type;
        $vistPlan->addedBy_id = Auth::id();
        if ($request->approved) {
            $vistPlan->status = 'approved';
            $vistPlan->team_admin_approved_at = now();
        } else {
            $vistPlan->status = 'pending';
            $vistPlan->team_admin_approved_at = null;
        }
        $vistPlan->save();

        return redirect()->back()->with('success', "{$vistPlan->type} Visit Plan Created Successfuly");
    }
    public function destroy(CustomerVisit $customerVisit)
    {
        //
    }
    public function employeeCustomerCheckAjas(Request $request)
    {

        if ($request->employee == 0) {
            $customer = Customer::where('employee_id', Auth::user()->employee->id)->select('id', 'customer_name', 'customer_code')->get();
        } else {
            $customer = Customer::where('employee_id', $request->employee)->select('id', 'customer_name', 'customer_code')->get();
        }

        if ($customer->count() > 0) {
            return response()->json([
                'success' => true,
                'customer' => $customer
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }
    public function customerVisitPlanStatusUpdate(Request $request)
    {
        $employee = Auth::user()->employee;
        if (!$employee->team_admin) {
            return back()->with('warning', 'You Are Not Team Leader');
        }
        $vistPlan = VisitPlan::find($request->visit);
        if ($employee->team_admin) {
            $employee_ids = $employee->myTeamMembers()->pluck('id');
            $addeByArray = Employee::whereIn('id', $employee_ids)->orWhere('id', $employee->id)->pluck('user_id')->toArray();
        } else {
            $addeByArray = Employee::where('id', $employee->id)->pluck('user_id')->toArray();
        }
        if (!in_array($vistPlan->addedBy_id, $addeByArray)) {
            return back()->with('warning', 'You Are Not able to Approve');
        }

        $vistPlan->status = 'approved';
        $vistPlan->team_admin_approved_at = now();
        $vistPlan->save();
        return back()->with('success', 'Visit Plan Updated Successfully');
    }
}
