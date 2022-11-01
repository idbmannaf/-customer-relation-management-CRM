<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CollectionHistory;
use App\Models\Convayance;
use App\Models\ConvayanceItems;
use App\Models\Employee;
use App\Models\EmployeePayment;
use App\Models\Invoice;
use App\Models\OfficeLocation;
use App\Models\Visit;
use App\Models\VisitPlan;
use Auth;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function billCollection(Request $request)
    {
        $type = $request->type;
        menuSubmenu('billCollection', 'billCollection_' . $type);
        if ($type == 'assign') {
            $prepear_collections_from_invoces = Invoice::with('assignedTo', 'assignedBy')->latest()->paginate(100);
            return view('admin.account.bill_collection', compact('prepear_collections_from_invoces'));
        }
    }
    public function assignToBillCollection(Request $request, Invoice $invoice)
    {
        return view('admin.account.assign_an_employee_for_bill_collection', compact('invoice'));
    }
    public function transectionHistory(Request $request)
    {
        if ($request->invoice) {
            $histories = CollectionHistory::where('invoice_id', $request->invoice)->latest()->paginate(100);
            return view('employee.customers.transectionHistory', compact('histories'));
        }
    }
    public function collectionList(Request $reqeust)
    {
        $type = $reqeust->type;
        menuSubmenu('collectionList',"collectionList_",$type);
        $collections = Visit::whereHas('visit_plan', function ($q) {
            $q->where('service_type', 'collection');
        })
            ->where('status', $type)
            ->latest()
            ->paginate(50);

            return view('admin.customer.collectionList', compact('collections'));
    }
    public function customerVisitPlansOfInvoice(Request $request, Invoice $invoice)
    {
        $visit_plans = VisitPlan::where('invoice_id', $invoice->id)->with('customer')->latest()->get();
        return view('admin.visitPlan.visit.visit_plan-of-incoice', compact('visit_plans', 'invoice'));
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

    public function allConvayances(Request $request)
    {
        $type = $request->type;
        menuSubmenu('allConvayances', 'CB_' . $type);
        if ($type == 'paid') {
            $convayance_bills = Convayance::where('paid', true)->where('status', '!=', null)->latest()->paginate(100);
        } else {
            $convayance_bills = Convayance::where('paid', false)->where('status', '!=', null)->where('status',$type)->latest()->paginate(100);
        }
        return view('admin.convayances.convances', compact('convayance_bills','type'));
    }

    public function convayancesDetails(Request $request, Convayance $convayance)
    {
        $convayance_items = ConvayanceItems::where('convayance_id', $convayance->id)->get();
        return view('admin.convayances.convancesDetails', compact('convayance', 'convayance_items'));
    }

    public function convayancesBillPayment(Request $request)
    {
        $type = $request->type;
        menuSubmenu('convayancesBillPayment', 'convayancesBillPayment_' . $type);
        if ($type == 'paid') {
            $convayance_bills = Convayance::where('status', 'approved')->where('paid', true)->where('status', '!=', null)->latest()->paginate(100);
        } else {
            $convayance_bills = Convayance::where('status', 'approved')->where('paid', false)->where('status', '!=', null)->latest()->paginate(100);
        }
        return view('admin.convayances.convancesPayment', compact('convayance_bills'));
    }

    public function convayancesBillPaymentDetails(Request $request, Convayance $convayance)
    {
        $convayance_items = ConvayanceItems::where('convayance_id', $convayance->id)->get();
        return view('admin.convayances.convancesPaymentDetails', compact('convayance', 'convayance_items'));
    }

    public function convayancesBillPaid(Request $request, Convayance $convayance)
    {
        $convayance->paid = true;
        $convayance->paid_at = now();
        $convayance->save();

        $employee_payment = new EmployeePayment;
        $employee_payment->employee_id = $convayance->employee_id;
        $employee_payment->visit_id = $convayance->visit_id;
        $employee_payment->convayance_id = $convayance->id;
        $employee_payment->paid_amount = $convayance->total_amount;
        $employee_payment->purpose = 'convayance';
        $employee_payment->note = "Convayance Bill Paid From Convyance Id: ({$convayance->id}) and Visit Plan ID: ({$convayance->visit_id})";
        $employee_payment->paid_by = Auth::id();
        $employee_payment->save();

        return redirect()->back()->with('success', 'Successfully Paid');
    }
    public function convayancesSubmit(Request $request, Visit $visit, Convayance $convayances)
    {
        // if ($this->is_my_employee($visit->employee_id == false)) {
        //     abort(403);
        // }
        if ($convayances->items->count() < 1) {
            return back()->with('warning', 'Please Add some Convayances Items');
        }
        $type = $request->submit;


        if ($type == 'pending') {
            $convayances->status = 'pending';
            $convayances->editedBy_id = Auth::id();
            $convayances->save();
        } elseif ($type == 'rejected') {
            $convayances->status = 'rejected';
            $convayances->rejected_at = now();
            $convayances->editedBy_id = Auth::id();
            $convayances->save();
        } elseif ($type == 'approved') {
            $convayances->status = 'approved';
            $convayances->approved_at = now();
            $convayances->editedBy_id = Auth::id();
            $convayances->save();
        }

        return redirect()->back()->with('success', 'Convayances Bill Claim Successfully Updated');
    }
}
