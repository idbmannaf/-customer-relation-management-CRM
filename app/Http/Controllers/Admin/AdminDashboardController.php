<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Call;
use App\Models\Challan;
use App\Models\Company;
use App\Models\Customer;
use App\Models\CustomerOffer;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\OfficeLocation;
use App\Models\Product;
use App\Models\Requisition;
use App\Models\RequisitionProduct;
use App\Models\UnusedRequisitionProduct;
use App\Models\User;
use App\Models\Visit;
use App\Models\VisitPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{

    public function index()
    {
        menuSubmenu('dashboard', 'dashboard');



        $total_employee = Employee::count();
        $today_attandance = Attendance::whereDate('created_at', now()->today())->count();
        $total_present = Attendance::whereDate('date', Carbon::now()->format('Y-m-d'))->where('logged_in_at', '!=', null)->count();
        $total_absent = Attendance::where('status', 'absent')->whereDate('date', Carbon::now()->format('Y-m-d'))->count();
        $total_late = Attendance::where('status', 'late_entry')->whereDate('date', Carbon::now()->format('Y-m-d'))->count();


        $total_pending_call = Call::where('approved_at', null)->count();
        $total_approved_call = Call::where('approved_at', '!=', null)->count();
        $total_done_call = Call::where('done_at', '!=', null)->count();
        $reff_calls =  Call::whereHas('refferTeamHeads')->count();

        $total_visit_plan = VisitPlan::count();
        $today_visit_plan = VisitPlan::whereDate('date_time', Carbon::now())->count();
        $pending_visit_plan = VisitPlan::where('status', 'pending')->count();
        $approved_visit_plan = VisitPlan::where('status', 'approved')->count();
        $completed_visit_plan = VisitPlan::where('status', 'completed')->count();

        $total_visit = Visit::count();
        $total_today_visit = Visit::whereDate('date_time', Carbon::now())->orderBy('date_time', 'DESC')->count();
        $total_pending_visit = Visit::where('status', 'pending')->count();
        $total_approved_visit = Visit::where('status', 'approved')->count();
        $total_completed_visit = Visit::where('status', 'completed')->count();

        $total_quatation = CustomerOffer::where('status', '!=', 'temp')->count();
        $pending_quatation = CustomerOffer::where('status', 'pending')->where('status', '!=', 'temp')->count();
        $approved_quatation = CustomerOffer::where('status', 'approved')->where('status', '!=', 'temp')->count();
        $customer_approved_quatation = CustomerOffer::where('status', '!=', 'temp')->where('customer_approved', 1)->count();
        $customer_not_approved_quatation = CustomerOffer::where('status', 'approved')->where('status', '!=', 'temp')->where('customer_approved', false)->count();


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

        $total_challan = Challan::latest()->count();
        $total_invoice = Invoice::latest()->count();

        $r_to_r_p = Requisition::where('type', 'product')->where('status', 'approved')->where('send_to_receive_by', '!=', null)->count();
        $r_to_r_sp = Requisition::where('type', 'spear_parts')->where('status', 'approved')->where('send_to_receive_by', '!=', null)->count();

        $unused_spear_parts = RequisitionProduct::where(function ($q) {
            $q->where('used', false);
            $q->orWhere('return_old_product', true);
        })->count();

        $unused_products = RequisitionProduct::where(function ($q) {
            $q->where('used', false);
            $q->orWhere('return_old_product', true);
        })->count();

        $receiveUnusedspear_parts = UnusedRequisitionProduct::where('type', 'spear_parts')->where('visit_id', '!=', null)->count();
        $receiveUnusedProduct = UnusedRequisitionProduct::where('type', 'product')->where('visit_id', '!=', null)->count();

        $receivedSpearPpartFromUnused = UnusedRequisitionProduct::where('type', 'spear_parts')->where('received', true)->count();
        $receivedProductFromUnused = UnusedRequisitionProduct::where('type', 'product')->where('received', true)->count();


        $repair_stock = UnusedRequisitionProduct::where('status', 'repair')->count();
        $recharge_stock = UnusedRequisitionProduct::where('status', 'recharge')->count();
        $bad_stock = UnusedRequisitionProduct::where('total_bad', '>', 0)->count();
        $reuse_stock = UnusedRequisitionProduct::where('total_reuse', '>', 0)->count();

        $received_spare_part_req = Requisition::where('status', 'approved')
            ->where('status', '!=', 'temp')
            ->where('type', 'spear_parts')
            ->count();
        $received_product_req = Requisition::where('status', 'approved')
            ->where('status', '!=', 'temp')
            ->where('type', 'spear_parts')
            ->count();

        $total_products = Product::where('product_type', 'products')->count();
        $total_spare_parts = Product::where('product_type', 'spare_parts')->count();
        $total_company = Company::where('active', true)->count();
        $total_employee = Employee::where('active', true)->count();
        $total_customer = Customer::where('active', true)->count();
        $total_office_location = OfficeLocation::where('active', true)->count();
        $total_department = Department::count();
        $total_designation = Designation::count();
        // flash('Welcome to onlinecode!')->warning();
        return view('admin.dashboard', compact(
            'total_present',
            'total_absent',
            'total_late',
            'total_approved_call',
            'total_pending_call',
            'total_done_call',
            'reff_calls',
            'today_visit_plan',
            'pending_visit_plan',
            'approved_visit_plan',
            'completed_visit_plan',
            'total_today_visit',
            'total_visit',
            'total_pending_visit',
            'total_approved_visit',
            'total_completed_visit',
            'total_quatation',
            'pending_quatation',
            'approved_quatation',
            'customer_approved_quatation',
            'customer_not_approved_quatation',
            'pending_product_rq',
            'reviewed_product_rq',
            'approved_product_rq',
            'pending_sp_rq',
            'reviewed_sp_rq',
            'approved_sp_rq',
            'r_to_r_p',
            'r_to_r_sp',
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
            'received_spare_part_req',
            'received_product_req',
            'total_products',
            'total_spare_parts',

            'total_company',
            'total_employee',
            'total_customer',
            'total_office_location',
            'total_department',
            'total_designation',
            'today_attandance',
            'total_visit_plan',


            'total_challan',
            'total_invoice'


        ));
    }
    public function selectUserForAssignRole(Request $request)
    {
        $users = User::where('username', 'like', '%' . $request->q . '%')
            ->orWhere('name', 'like', '%' . $request->q . '%')
            ->select(['id', 'name', 'username'])->take(30)->get();
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
    public function myProfile()
    {
        menuSubmenu('dashboard', 'profile');
        $user = Auth::user();
        return view('admin.myProfile', compact('user'));
    }
    public function editMyProfile()
    {
        $user = Auth::user();
        return view('admin.editMyProfile', compact('user'));
    }
    public function updateMyProfile()
    {
    }
}
