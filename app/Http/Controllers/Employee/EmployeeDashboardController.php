<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Call;
use App\Models\Challan;
use App\Models\Company;
use App\Models\Customer;
use App\Models\CustomerOffer;
use App\Models\Department;
use App\Models\Designation;
use App\Models\District;
use App\Models\Division;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\OfficeLocation;
use App\Models\Requisition;
use App\Models\RequisitionProduct;
use App\Models\Team;
use App\Models\UnusedRequisitionProduct;
use App\Models\Upazila;
use App\Models\User;
use App\Models\UserLocation;
use App\Models\Visit;
use App\Models\VisitPlan;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EmployeeDashboardController extends Controller
{
    use EmployeeTrait;
    public function __construct()
    {
        if (Auth::check()) {
            if (!Auth::user()->employee) {
                abort(404);
            }
        }
    }

    public function index(Request $request)
    {
        menuSubmenu('employee', 'employeeDashboard');

        $myCash = Visit::where('employee_id', $this->alEmployees())->whereHas('visit_plan', function ($q) {
            $q->where('service_type', 'collection');
        })
            ->where('status', 'pending')
            // ->where('status', '!=', 'completed')
            ->sum('collection_amount');

        $employee = Auth::user()->employee;
        $myCustomer = $this->my_customers()->count();
        $visitPlans = VisitPlan::whereIn('employee_id', $this->alEmployees())->orderBy('date_time')->paginate(15);
        if ($employee->team_admin) {
            if ($employee->company->access_all_call && $employee->company->access_all_call_visit_plan_without_call) {
                $today_visit_plan = VisitPlan::whereDate('date_time', Carbon::now())->count();
                $pending_visit_plan = VisitPlan::where('status', 'pending')->count();
                $approved_visit_plan = VisitPlan::where('status', 'approved')->count();
                $completed_visit_plan = VisitPlan::where('status', 'completed')->count();
            } elseif ($employee->company->access_all_call) {
                $today_visit_plan = VisitPlan::whereDate('date_time', Carbon::now())->where('call_id', '!=', null)->count();
                $pending_visit_plan = VisitPlan::where('status', 'pending')->where('call_id', '!=', null)->count();
                $approved_visit_plan = VisitPlan::where('status', 'approved')->where('call_id', '!=', null)->count();
                $completed_visit_plan = VisitPlan::where('status', 'completed')->where('call_id', '!=', null)->count();
            } elseif ($employee->company->access_all_call_visit_plan_without_call) {
                $today_visit_plan = VisitPlan::whereDate('date_time', Carbon::now())->where('call_id', null)->count();
                $pending_visit_plan = VisitPlan::where('status', 'pending')->where('call_id', null)->count();
                $approved_visit_plan = VisitPlan::where('status', 'approved')->where('call_id', null)->count();
                $completed_visit_plan = VisitPlan::where('status', 'completed')->where('call_id', null)->count();
            } else {
                $today_visit_plan = VisitPlan::whereDate('date_time', Carbon::now())->whereIn('employee_id', $this->alEmployees())->count();
                $pending_visit_plan = VisitPlan::where('status', 'pending')->whereIn('employee_id', $this->alEmployees())->count();
                $approved_visit_plan = VisitPlan::where('status', 'approved')->whereIn('employee_id', $this->alEmployees())->count();
                $completed_visit_plan = VisitPlan::where('status', 'completed')->whereIn('employee_id', $this->alEmployees())->count();
            }
        } else {
            $today_visit_plan = VisitPlan::whereDate('date_time', Carbon::now())->whereIn('employee_id', $this->alEmployees())->count();
            $pending_visit_plan = VisitPlan::where('status', 'pending')->whereIn('employee_id', $this->alEmployees())->count();
            $approved_visit_plan = VisitPlan::where('status', 'approved')->whereIn('employee_id', $this->alEmployees())->count();
            $completed_visit_plan = VisitPlan::where('status', 'completed')->whereIn('employee_id', $this->alEmployees())->count();
        }

        $total_pending_visit = Visit::whereIn('employee_id', $this->alEmployees())->where('status', 'pending')->count();
        $total_approved_visit = Visit::whereIn('employee_id', $this->alEmployees())->where('status', 'approved')->count();
        $total_completed_visit = Visit::whereIn('employee_id', $this->alEmployees())->where('status', 'completed')->count();
        if ($employee->team_admin && $employee->company->inventory_maintain_permission) {
            $pending_product_rq = Requisition::where('type', 'product')
                ->where('status', 'pending')
                ->count();
            $reviewed_product_rq = Requisition::where('type', 'product')
                ->where('status', 'reviewed')
                ->count();
            $approved_product_rq = Requisition::where('type', 'product')
                ->where('status', 'approved')
                ->count();

            $pending_sp_rq = Requisition::where('type', 'spear_parts')
                ->where('status', 'pending')
                ->count();
            $reviewed_sp_rq = Requisition::where('type', 'spear_parts')
                ->where('status', 'reviewed')
                ->count();
            $approved_sp_rq = Requisition::where('type', 'spear_parts')
                ->where('status', 'approved')
                ->count();
        } else {
            $pending_product_rq = Requisition::where('type', 'product')
                ->where('status', 'pending')
                ->where('employee_id', $this->alEmployees())
                ->count();
            $reviewed_product_rq = Requisition::where('type', 'product')
                ->where('status', 'reviewed')
                ->where('employee_id', $this->alEmployees())
                ->count();
            $approved_product_rq = Requisition::where('type', 'product')
                ->where('status', 'approved')
                ->where('employee_id', $this->alEmployees())
                ->count();

            $pending_sp_rq = Requisition::where('type', 'spear_parts')
                ->where('status', 'pending')
                ->where('employee_id', $this->alEmployees())
                ->count();
            $reviewed_sp_rq = Requisition::where('type', 'spear_parts')
                ->where('status', 'reviewed')
                ->where('employee_id', $this->alEmployees())
                ->count();
            $approved_sp_rq = Requisition::where('type', 'spear_parts')
                ->where('status', 'approved')
                ->where('employee_id', $this->alEmployees())
                ->count();
        }


        $customer_ids = $this->my_customers()->pluck('id');
        $total_challan = Challan::whereIn('customer_id', $customer_ids)->count();
        $total_invoice = Invoice::whereIn('customer_id', $customer_ids)->count();

        $customer_ids =  Customer::whereIn('employee_id', $this->alEmployees())
            ->pluck('id')
            ->toArray();
        if ($employee->company->access_all_call && $employee->team_admin) {
            $service_calls = Call::orderBy('date_time', 'DESC')
                ->latest()
                ->paginate(15);
        } else {
            $service_calls = Call::where(function ($q) use ($customer_ids) {
                $q->whereIn('customer_id', $customer_ids);
                $q->orWhereIn('employee_id', $this->alEmployees());
            })->orderBy('date_time', 'DESC')->paginate(15);
        }


        if ($employee->company->access_all_call && $employee->team_admin) {
            $pending_calls = Call::where('approved_at', null)
                ->count();

            $approved_calls = Call::where('approved_at', '!=', null)
                ->count();

            $done_calls = Call::where('done_at', '!=', null)
                ->latest()
                ->paginate(100);;
        } else {
            $pending_calls = Call::where(function ($q) use ($customer_ids) {
                $q->whereIn('customer_id', $customer_ids);
                $q->orWhereIn('employee_id', $this->alEmployees());
            })
                ->where('approved_at', null)
                ->count();

            $approved_calls = Call::where(function ($q) use ($customer_ids) {
                $q->whereIn('customer_id', $customer_ids);
                $q->orWhereIn('employee_id', $this->alEmployees());
            })->where('approved_at', '!=', null)
                ->where('done_at', null)
                ->count();

            $done_calls = Call::where(function ($q) use ($customer_ids) {
                $q->whereIn('customer_id', $customer_ids);
                $q->orWhereIn('employee_id', $this->alEmployees());
            })->where('approved_at', '!=', null)
                ->where('done_at', '!=', null)
                ->count();
        }
        $reff_calls =  $employee->refferCalls()->count();

        $r_to_r_p = Requisition::whereIn('employee_id', $this->alEmployees())->where('type', 'product')->where('status', 'approved')->where('send_to_receive_by', '!=', null)->count();
        $r_to_r_sp = Requisition::whereIn('employee_id',  $this->alEmployees())->where('type', 'spear_parts')->where('status', 'approved')->where('send_to_receive_by', '!=', null)->count();



        $customers = $this->my_customers()->pluck('id');
        $pending_offer_quot = CustomerOffer::whereIn('customer_id', $customers)
            ->where('status', 'pending')
            ->count();
        $approved_offer_quot = CustomerOffer::whereIn('customer_id', $customers)
            ->where('status', 'approved')
            ->count();

        $rejected_offer_quot = CustomerOffer::whereIn('customer_id', $customers)
            ->where('status', 'rejected')
            ->count();

        $customer_approved_offer_quot = CustomerOffer::whereIn('customer_id', $customers)->where('status', 'approved')
            ->where('status', '!=', 'temp')
            ->where('customer_approved', true)
            ->count();

        $customer_not_approved_offer_quot = CustomerOffer::whereIn('customer_id', $customers)->where('status', '!=', 'temp')
            ->where('status', 'approved')
            ->where('customer_approved', false)
            ->with('items')
            ->count();


        $spear_parts_requisition_ids = Requisition::whereIn('employee_id', $this->alEmployees())->where('type', 'spear_parts')->where('status', 'approved')->pluck('id');
        $product_requisition_ids = Requisition::whereIn('employee_id', $this->alEmployees())->where('type', 'product')->where('status', 'approved')->pluck('id');

        $unused_spear_parts = RequisitionProduct::whereIn('requisition_id', $spear_parts_requisition_ids)->where(function ($q) {
            $q->where('used', false);
            $q->orWhere('return_old_product', true);
        })->count();

        $unused_products = RequisitionProduct::whereIn('requisition_id', $product_requisition_ids)->where(function ($q) {
            $q->where('used', false);
            $q->orWhere('return_old_product', true);
        })->count();

        $receiveUnusedspear_parts = UnusedRequisitionProduct::where('type', 'spear_parts')->where('visit_id', '!=', null)->count();
        $receiveUnusedProduct = UnusedRequisitionProduct::where('type', 'product')->where('visit_id', '!=', null)->count();
        $receivedSpearPpartFromUnused = UnusedRequisitionProduct::where('type', 'spear_parts')->where('received', true)->count();
        $receivedProductFromUnused = UnusedRequisitionProduct::where('type', 'product')->where('received', true)->count();

        $repair_stock = UnusedRequisitionProduct::where('status', 'repair')->count();
        $recharge_stock = UnusedRequisitionProduct::where('status', 'recharge')->count();
        $bad_stock = UnusedRequisitionProduct::where('total_bad','>', 0)->count();
        $reuse_stock = UnusedRequisitionProduct::where('total_reuse','>', 0)->count();

        return view('employee.employeeDashboard', compact(
            'employee',
            'myCustomer',
            'visitPlans',
            'today_visit_plan',
            'pending_visit_plan',
            'approved_visit_plan',
            'completed_visit_plan',
            'total_pending_visit',
            'total_approved_visit',
            'total_completed_visit',
            'pending_product_rq',
            'reviewed_product_rq',
            'approved_product_rq',
            'pending_sp_rq',
            'reviewed_sp_rq',
            'approved_sp_rq',
            'total_challan',
            'total_invoice',
            'service_calls',
            'r_to_r_p',
            'r_to_r_sp',
            'myCash',
            'pending_calls',
            'approved_calls',
            'done_calls',
            'reff_calls',
            'pending_offer_quot',
            'approved_offer_quot',
            'rejected_offer_quot',
            'customer_approved_offer_quot',
            'customer_not_approved_offer_quot',
            'unused_spear_parts',
            'unused_products',
            'receiveUnusedspear_parts',
            'receiveUnusedProduct',
            'receivedSpearPpartFromUnused',
            'receivedProductFromUnused',
            'repair_stock',
            'recharge_stock',
            'bad_stock',
            'reuse_stock',


        ));
    }
    public function myProfile(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;
        return view('employee.profile', compact('user', 'employee'));
    }
    public function editMyProfile(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;
        $companies = Company::where('active', true)->get();
        $designations = Designation::latest()->get();
        $departments = Department::latest()->get();
        return view('employee.profileEdit', compact('employee', 'companies', 'designations', 'departments'));
    }

    public function updateMyProfile(Request $request, Employee $employee)
    {
        $user = $employee->user;
        if ($request->type == 'password') {
            $request->validate([
                'old_password' => 'required',
                'password' => 'required|confirmed|min:5',
            ]);
            if (!password_verify($user->password, $request->password)) {
                return redirect()->back()->with('warning', 'Old Password Dose\'nt Match');
            } else {
                $user->password = Hash::make($request->password);
                $user->temp_password = $request->password;
                $user->save();
                return redirect()->back()->with('success', 'Password Updated Successfully');
            }
        } else {
            $request->validate([
                'name' => 'required',
            ]);
            $employee->name = $request->name;
            $employee->save();
            if ($file = $request->avater) {
                $f = 'users/' . $user->avater;
                if (Storage::disk('public')->exists($f)) {
                    Storage::disk('public')->delete($f);
                }

                $extension = $file->getClientOriginalExtension();
                $randomFileName = $user->id . Str::slug($user->name ?? 'No Name Found') . '_banner_' . date('Y_m_d_his') . '_' . rand(100, 999) . '.' . $extension;
                Storage::disk('public')->put('users/' . $randomFileName, File::get($file));
                $user->avater = $randomFileName;
                $user->save();
            }

            return redirect()->back()->with('success', 'Employee Updated Successfully');
        }
    }





    public function viewUser(Request $request)
    {
        menuSubmenu('viewUser', 'viewUser');
        $user = User::find($request->user);
        if ($user->id != Auth::id()) {
            return back();
        }
        return view('users.view', compact('user'));
    }
    public function editUser(Request $request)
    {
        menuSubmenu('editUser', 'editUser');
        $user = User::find($request->user);
        if ($user->id != Auth::id()) {
            return back();
        }

        return view('users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);
        if ($request->type == 'user') {
            $request->validate([
                'name' => 'required',
                'email' => 'email|unique:users,email,' . $user->id,
                'image' => 'nullable |image|mimes:png,jpg'
            ]);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->track = $request->track_him ? 1 : 0;
            if ($file = $request->avater) {
                $f = 'users/' . $user->avater;
                if (Storage::disk('public')->exists($f)) {
                    Storage::disk('public')->delete($f);
                }

                $extension = $file->getClientOriginalExtension();
                $randomFileName = $user->id . Str::slug($user->name ?? 'No Name Found') . '_banner_' . date('Y_m_d_his') . '_' . rand(100, 999) . '.' . $extension;
                Storage::disk('public')->put('users/' . $randomFileName, File::get($file));
                $user->avater = $randomFileName;
            }
            $user->save();
        } else {
            $request->validate([
                'old_password' => 'required',
                'password' => 'required|confirmed|min:5'
            ]);
            if (password_verify($request->old_password, $user->password)) {
                $user->password = Hash::make($request->password);
                $user->temp_password = $request->password;
                $user->save();
            } else {
                return redirect()->back()->with('success', 'Old Password Dose not matched');
            }
        }

        return redirect()->back()->with('success', "{$request->type} Updated Successfully");
    }
    public function locationSet(Request $request)
    {
        $user = Auth::user();
        if ($user->track) {
            $location = UserLocation::where('user_id', Auth::id())->where('created_at', '>', now()->subMinutes(4))->latest()->first();

            if (!$location) {
                $location = new UserLocation;
                $location->lat = $request->lat;
                $location->lng = $request->lng;
                $location->ip = $request->ip();
                $location->user_id = $user->id;
                $location->addedBy_id = Auth::id();
                $location->save();

                if ($user->officeLocation) {
                    $radius = .2;
                    $company_lat = $user->officeLocation->lat ?? 23.7614067;
                    $company_lng = $user->officeLocation->lng ?? 90.4195412;
                    if ($company_lat and $company_lng) {
                        $haversine = "(6371 * acos(cos(radians(" . $company_lat . "))
                                        * cos(radians(`lat`))
                                        * cos(radians(`lng`)
                                        - radians(" . $company_lng . "))
                                        + sin(radians(" . $company_lat . "))
                                        * sin(radians(`lat`))))";
                    }

                    $user_lcation = UserLocation::select('id', 'lat', 'lng')
                        ->where('user_id', $user->id)
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
                            $attendance->ip = $request->ip();
                            $attendance->logged_out_at = now();
                            $attendance->save();
                        } else {
                            $attendance = new Attendance;
                            $attendance->user_id = $user->id;
                            $attendance->date = date('Y-m-d');
                            $attendance->company_id = $user->officeLocation->company_id;
                            $attendance->lat = $user_lcation->lat;
                            $attendance->lng = $user_lcation->lng;
                            $attendance->ip = $request->ip();
                            $attendance->logged_in_at = now();
                            $attendance->logged_out_at = now();
                            $attendance->save();
                        }

                        $this->Wo_RunInBackground(['lat' => $location->lat, 'lng' => $location->lng, 'success' => true,]);
                        $this->locationNameSet($request, $location);
                        return response()->json([
                            'success' => true,
                            'lat' => $location->lat,
                            'lng' => $location->lng,
                        ]);
                    }
                    return response()->json([
                        'success' => false,
                        'lat' => $location->lat,
                        'lng' => $location->lng,
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'lat' => $location->lat,
                        'lng' => $location->lng,
                    ]);
                }
            }
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }

    public function locationNameSet($request, $location)
    {
        $url = url("http://poi.18gps.net/poi?lat={$request->lat}&lon={$request->lng}");
        $client = new Client();
        $r = $client->request('GET', $url);
        $result = $r->getBody()->getContents();
        $location->location = $result ?? '';
        $location->save();
    }

    public function Wo_RunInBackground($data = array())
    {
        if (!empty(ob_get_status())) {
            ob_end_clean();
            header("Content-Encoding: none");
            header("Connection: close");
            ignore_user_abort();
            ob_start();
            if (!empty($data)) {
                header('Content-Type: application/json');
                echo json_encode($data);
            }
            $size = ob_get_length();
            header("Content-Length: $size");
            ob_end_flush();
            flush();
            session_write_close();
            if (is_callable('fastcgi_finish_request')) {
                fastcgi_finish_request();
            }
        }
    }


    public function addMyCustomers(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;
        if ($request->isMethod('get')) {
            $divisions = DB::table('divisions')->select(['id', 'name'])->orderBy('name')->get();
            $districts = DB::table('districts')->select(['id', 'name', 'division_id'])->orderBy('name')->get();
            $thanas = DB::table('upazilas')->select(['id', 'name', 'district_id', 'division_id'])->orderBy('name')->get();
            $companies = Company::where('active', true)->get();
            return view('employee.addMyCustomers', compact('companies', 'divisions', 'districts', 'thanas', 'employee'));
        }
        if ($request->isMethod('post')) {
            $request->validate([
                'email' => 'required|email|unique:customers,email',
                'company_name' => 'nullable',
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
            $customer->company_name = $request->company_name;
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
            return back()->with('success', 'Customer added Successfully Created.');
        }
        return back();
    }
}
