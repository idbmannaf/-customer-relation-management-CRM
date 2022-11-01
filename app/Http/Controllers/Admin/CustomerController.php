<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\ImportCustomer;
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
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:customer-list', ['only' => ['index']]);
        $this->middleware('permission:customer-search', ['only' => ['customerSearch']]);
        $this->middleware('permission:customer-add', ['only' => ['store']]);
        $this->middleware('permission:customer-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:customer-delete', ['only' => ['destroy']]);
        $this->middleware('permission:customer-bulk-upload', ['only' => ['importCustomer']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        menuSubmenu('customers', 'allCustomers');
        $total_customer = Customer::count();
        $customers = Customer::with('user', 'company')->latest()->paginate(30);
        $q = '';
        if ($request->ajax()) {
            return view('admin.customer.ajax.customersAjax', compact('q', 'customers'))->render();
        }

        return view('admin.customer.index', compact('customers', 'q','total_customer'));
    }

    public function customerTransactionHistory(Request $request, Customer $customer)
    {
        $transaction_histories = CollectionHistory::where('customer_id', $customer->id)->latest()->paginate(100);
        return view('admin.customer.transaction_histories', compact('transaction_histories'));
    }

    public function customer_to_location(Request $request)
    {
        $customers = Customer::where('client_address','!=',null)->get();
        foreach ($customers as  $item) {
            $office = OfficeLocation::where('title',$item->client_address)->first();
            if (!$office) {
                $office = new OfficeLocation;
                $office->title = $item->client_address;
                $office->customer_company_id = $item->company_id;
                $division = Division::where('name',$item->division)->first();
                $district = District::where('name',$item->district)->first();
                $office->division_id =   $division ?  $division->id : null;
                $office->district_id =   $district ?  $district->id : null;
                $office->active = true;
                $office->address = $item->address;
                $office->mobile_number = $item->phone;
                $office->save();
            }

        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        menuSubmenu('customers', 'addCustomers');
        $divisions = DB::table('divisions')->select(['id', 'name'])->orderBy('name')->get();
        $districts = DB::table('districts')->select(['id', 'name', 'division_id'])->orderBy('name')->get();
        $thanas = DB::table('upazilas')->select(['id', 'name', 'district_id', 'division_id'])->orderBy('name')->get();
        $companies = CustomerCompany::where('active', true)->get();
        return view('admin.customer.create', compact('companies', 'divisions', 'districts', 'thanas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:customers,email',
            'employee' => 'required',
            'company_id' => 'required',
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
        $employee = Employee::find($request->employee);
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
        $customer->area_id = $request->area_id;
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
        return back()->with('success', 'New Customer Account Successfully Created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        $companies = CustomerCompany::where('active', true)->get();
        $divisions = DB::table('divisions')->select(['id', 'name'])->orderBy('name')->get();
        $districts = DB::table('districts')->select(['id', 'name', 'division_id'])->orderBy('name')->get();
        $thanas = DB::table('upazilas')->select(['id', 'name', 'district_id', 'division_id'])->orderBy('name')->get();
        $div= Division::where('name','like','%'.$customer->division.'%')->first();
        $dis= District::where('name','like','%'.$customer->district.'%')->first();

        // $selectedDistricts = District::where('name', 'like', "%" . ->first()->id;
        // dd($selectedDistricts);
        $selectedDistricts = District::where('division_id', $div->id)->get();
        $selectedThanas = Upazila::where('district_id', $dis->id)->get();
        return view('admin.customer.edit', compact('customer', 'companies', 'divisions', 'districts', 'thanas', 'selectedDistricts', 'selectedThanas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {

        $user =$customer->user;
        $request->validate([
            'email'=>'required|email|unique:users,username,'.$user->id,
            'employee' => 'required',
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
        $employee = Employee::find($request->employee);

        if ($request->email != $customer->user->username) {
            $user->name = $request->customer_name;
            $user->username = $request->email;
            $user->track = true;
            $user->save();
        }

        $customer->user_id = $user->id;
        $customer->employee_id = $employee->id;
        $customer->company_id = $request->company_name;
        $customer->employee_name = $employee->name;
        $customer->customer_code = $request->customer_code;
        $customer->customer_name = $request->customer_name;
        $customer->client_address = $request->client_address;
        $customer->phone = $request->phone;
        $customer->area_id = $request->area_id;
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->back()->with('success', 'Customer Successfully Deleted');
    }
    public function selectNewRole(Request $request)
    {
        if ($request->type == 'employee') {
            $users = User::doesntHave('employee')
                ->doesntHave('customer')
                ->where(function ($q) use ($request) {
                    $q->orWhere('username', 'like', '%' . $request->q . '%');
                    $q->orWhere('name', 'like', '%' . $request->q . '%');
                    $q->orWhere('email', 'like', '%' . $request->q . '%');
                })
                ->select(['id', 'username', 'email', 'name'])
                ->take(30)
                ->get();
            // ->orWhere('name', 'like', '%'.$request->q.'%')

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
        if ($request->type == 'only_employee') {
            $users = Employee::orWhere('employee_id', 'like', '%' . $request->q . '%')
                ->orWhere('name', 'like', '%' . $request->q . '%')

                ->select(['id', 'employee_id', 'name'])
                ->take(30)
                ->get();
            // ->orWhere('name', 'like', '%'.$request->q.'%')

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
        if ($request->type == 'company') {
            $companies = Company::where('id', 'like', '%' . $request->q . '%')
                ->orWhere('name', 'like', '%' . $request->q . '%')
                ->select(['id', 'name'])
                ->take(30)
                ->get();

            if ($companies->count()) {
                if ($request->ajax()) {
                    return $companies;
                }
            } else {
                if ($request->ajax()) {
                    return $companies;
                }
            }
        }
        $users = User::doesntHave('employee')
            ->doesntHave('customer')
            ->where(function ($q) use ($request) {
                $q->orWhere('username', 'like', '%' . $request->q . '%');
                $q->orWhere('name', 'like', '%' . $request->q . '%');
                $q->orWhere('email', 'like', '%' . $request->q . '%');
            })
            ->select(['id', 'username', 'email', 'name'])
            ->take(30)
            ->get();

        if ($users->count()) {
            if ($request->ajax()) {
                return $users;
            }
        } else {
            if ($request->ajax()) {
                return $users;
            }
        }
    }
    public function selectNewRoleWithoutCustomer(Request $request)
    {
        $users = User::where('username', 'like', '%' . $request->q . '%')
            ->orWhere('username', 'like', '%'.$request->q.'%')
            // ->orWhere('name', 'like', '%'.$request->q.'%')
            ->orWhere('name', 'like', '%' . $request->q . '%')
            ->select('id','username','name')
            ->take(30)
            ->get();
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
    public function importCustomer(Request $request)
    {
        $request->validate([
            'file' => 'required'
        ]);
        if ($request->file->getClientOriginalExtension() != 'xlsx') {
            return redirect()->back()->with('error', "Only xlsx File Allowed");
        }
        $upload = Excel::import(new ImportCustomer, request()->file('file'),);
        if ($upload) {
            return redirect()->back()->with('success', "Customers Added Successfully");
        }
    }

    public function customerSearch(Request $request)
    {
        $q = $request->q;
        $customers = Customer:: where('customer_name', 'like', '%' . $q . '%')
        ->orWhere('company_name', 'like', '%' . $q . '%')
            ->orWhereHas('employee', function ($query) use ($q) {
                $query->where('name', 'like', '%' . $q . '%');
                $query->orWhere('employee_id', 'like', '%' . $q . '%');
            })
            ->orWhere('id',$q)
            ->orWhere('email', 'like', '%' . $q . '%')
            ->orWhere('mobile', 'like', '%' . $q . '%')
            ->orWhere('customer_code', 'like', '%' . $q . '%')
            ->orWhere('phone', 'like', '%' . $q . '%')
            ->with('user')
            ->latest()
            ->paginate(30);
        return view('admin.customer.ajax.customersAjax', compact('q', 'customers'))->render();
    }
}
