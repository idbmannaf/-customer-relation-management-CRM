<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Call;
use App\Models\CallReferencePevot;
use App\Models\CallToSendProductRequest;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\OfficeLocation;
use App\Models\Product;
use App\Models\VisitPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CallController extends Controller
{
    public function calls(Request $request)
    {

        $type = $request->type ?? 'all';
        menuSubmenu('serviceColls', $type.'_serviceColls');
        if ($type == 'pending') {
            $calls = Call::where('approved_at', null)
                ->latest()
                ->paginate(50);
        } elseif ($type == 'approved') {
            $calls = Call::where('approved_at', '!=', null)
                ->latest()
                ->paginate(50);
        } elseif ($type == 'done') {
            $calls = Call::where('done_at', '!=', null)
            ->latest()
            ->paginate(50);
        }else {
            menuSubmenu('calls', 'allCalls');
            $calls = Call::latest()
                ->paginate(50);
        }
        return view('admin.calls.calls', compact('calls', 'type'));
    }
    public function refferedCall()
    {
        menuSubmenu('calls', 'refferedCalls');
        $calls = Call::Has('refferTeamHeads')->latest()->get();
        return view('admin.calls.refferedCalls', compact('calls'));
    }
    public function customersAllAjax(Request $request)
    {
        $users = Customer::where('customer_code', 'like', '%' . $request->q . '%')
            ->orWhere('customer_name', 'like', '%' . $request->q . '%')
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
    public function employeesAllAjax(Request $request)
    {
        $users = Employee::where('employee_id', 'like', '%' . $request->q . '%')
            ->orWhere('name', 'like', '%' . $request->q . '%')
            ->select(['id', 'employee_id', 'name'])->take(30)->get();
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
    public function productAllAjax(Request $request)
    {
        // dd($request->all());

        if ($request->category) {

            $products = Product::where('category_id', $request->category)
                ->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->q . '%');
                    $q->orWhere('model', 'like', '%' . $request->q . '%');
                })
                ->select(['id', 'model', 'name'])->take(30)->get();
        } else {
            if ($request->type) {
                $products = Product::where('product_type', $request->type)->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->q . '%');
                    $q->orWhere('model', 'like', '%' . $request->q . '%');
                })
                    ->select(['id', 'model', 'name'])->take(30)->get();
            } else {
                $products = Product::where('name', 'like', '%' . $request->q . '%')
                    ->orWhere('model', 'like', '%' . $request->q . '%')
                    ->select(['id', 'model', 'name'])->take(30)->get();
            }
        }


        if ($products->count()) {
            if ($request->ajax()) {
                return $products;
            }
        } else {
            if ($request->ajax()) {
                return $products;
            }
        }
    }

    public function getCustomerOffice(Request $request)
    {
        $customer = Customer::find($request->customer);
        $customer_company_offices = OfficeLocation::where('customer_company_id', $customer->company_id)->get();

        return response()->json([
            'success' => true,
            'html' => view('employee.visitPlan.ajax.customersCompanyOfficeOption', compact('customer_company_offices', 'customer'))->render()
        ]);
        if ($customer_company_offices->count()) {
            return response()->json([
                'success' => true,
                'html' => view('employee.visitPlan.ajax.customersCompanyOfficeOption', compact('customer_company_offices', 'customer'))->render()
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }
    public function addCalls(Request $request)
    {

        if ($request->isMethod('get')) {
            $customers =  Customer::where('active', true)->get();
            $team_admins = Employee::where('active', true)->where('team_admin', true)->orderBy('company_id')->get();
            // dd($team_admins);
            return view('admin.calls.addCalls', compact('customers', 'team_admins'));
        }

        if ($request->isMethod('post')) {
            $request->validate([
                'date' => 'required|date',
                'time' => 'required',
                'customer' => 'required',
                'employee' => 'required',
                'type' => 'required',
                'purpose_of_visit' => 'nullable',
                'customer_address' => 'required',
            ]);

            $date_time = $request->date . " " . $request->time . ":00";
            $call = new Call;
            $call->date_time = $date_time;
            $call->customer_id = $request->customer;
            $call->employee_id = $request->employee;
            $call->service_address = $request->service_address;
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
            if (!$call->approved_by && $call->approved_at) {
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
            return redirect()->back()->with('success', 'Call/Task/Complain Added Successfully');
        }
    }

    public function updateCalls(Request $request, Call $call)
    {
        if ($request->isMethod('get')) {
            $customers =  Customer::where('active', true)->get();
            $customer_office = OfficeLocation::where('company_id', $call->company_id)->get();
            $myEmployees = Employee::where('active', true)->get();
            $team_admins = Employee::where('active', true)->where('team_admin', true)->orderBy('company_id')->get();
            return view('admin.calls.editCalls', compact('call', 'customers', 'customer_office', 'myEmployees', 'team_admins'));
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
                'service_address' => 'required',
            ]);

            $date_time = $request->date . " " . $request->time . ":00";
            $call->date_time = $date_time;
            $call->employee_id = $request->employee;
            $call->customer_id = $request->customer;
            $call->service_address = $request->service_address;
            if (is_numeric($request->customer_address)) {
                $call->customer_address = OfficeLocation::find($request->customer_address)->title;
            } else {
                $call->customer_address = $request->customer_address;
            }
            $call->inhouse_product = $request->inhouse_product ? 1 : 0;
            $call->purpose_of_visit = $request->purpose_of_visit;
            $call->admin_note = $request->admin_note;
            $call->approved_at = $request->approved_at ? now() : null;
            $call->editedBy_id = Auth::id();
            $call->save();
            if (!$call->approved_by && $call->approved_at) {
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

        $visit_plans = VisitPlan::where('call_id', $call->id)->orderBy('date_time', 'DESC')->get();
        return view('admin.calls.vistiPlan.callWiseVistiPlan', compact('call', 'visit_plans'));
    }
    public function addVisitPlan(Request $request, Call $call)
    {


        if ($request->isMethod('get')) {
            $customer =  $call->customer;
            $my_employees = Employee::where('active', true)->get();
            $customers = Customer::where('active', true)->get();

            $customer_office_loations = $call->customer->company->offices;
            return view('admin.calls.vistiPlan.createCallWiseVistiPlan', compact('call',  'customer', 'customers', 'my_employees', 'customer_office_loations'));
        }
        if ($request->isMethod('post')) {
            if ($call->visitPlan->count()) {
                return redirect()->back()->with('warning', 'This Call Has Already Visit Plan');
            }

            $request->validate([
                'date' => 'required|date',
                'time' => 'required',
                'employee' => 'required',
                'purpose_of_visit' => 'nullable|string',
                'customer_address' => 'required',
                'service_type' => 'required',
                'service_address'=>'nullable'
            ]);
            if ($request->service_type == 'service') {
                $request->validate([
                    'service_address' => 'required'
                ]);
            }

            $date_time = $request->date . " " . $request->time . ":00";
            $vistPlan = new VisitPlan;
            $vistPlan->call_id = $call->id;
            $vistPlan->employee_id = $request->employee;
            $vistPlan->customer_id = $request->customer ?? $call->customer_id;
            $vistPlan->date_time = $date_time;
            $vistPlan->service_type = $request->service_type;
            $vistPlan->purpose_of_visit = $request->purpose_of_visit;
            $vistPlan->payment_collection_date = $request->payment_collection_date ?? null;
            $vistPlan->payment_maturity_date = $request->payment_maturity_date ?? null;

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
            return redirect()->back()->with('success', "Visit Plan Created Successfuly");
        }
    }
    public function sendRequestToTheCustomer(Request $request, Call $call)
    {
        return view('admin.calls.request_to_the_customer', compact('call'));
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
            })->latest()->paginate(50);
        } elseif ($type == 'received') {
            $products = CallToSendProductRequest::where('submited', true)->where('received', true)->whereHas('call', function ($q) {
                $q->where('done_at',  null);
            })->latest()->paginate(50);
        } elseif ($type == 'not_received') {
            $products = CallToSendProductRequest::where('submited', true)->where('sent', true)->where('received', false)->whereHas('call', function ($q) {
                $q->where('done_at',  null);
            })->latest()->paginate(50);
        } elseif ($type == 'ready_for_delivered') {
            $products = CallToSendProductRequest::where('received', true)->where('delivered', false)->whereHas('call', function ($q) {
                $q->where('done_at', "!=", null);
            })->latest()->paginate(50);
        } elseif ($type == 'delivered') {
            $products = CallToSendProductRequest::where('received', true)->where('delivered', true)->whereHas('call', function ($q) {
                $q->where('done_at', "!=", null);
            })->latest()->paginate(50);
        } elseif ($type == 'customer_received') {
            $products = CallToSendProductRequest::where('delivered', true)->where('customer_received', true)->whereHas('call', function ($q) {
                $q->where('done_at', "!=", null);
            })->latest()->paginate(50);
        }
        return view('admin.calls.customer_product_sent', compact('products', 'type'));
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
