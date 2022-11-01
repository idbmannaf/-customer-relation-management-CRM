<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Convayance;
use App\Models\ConvayanceItems;
use App\Models\EmployeePayment;
use App\Models\UserLocation;
use App\Models\Visit;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ConvayanceController extends Controller
{
    use EmployeeTrait;
    public function convayances(Request $request, Visit $visit)
    {
        // if ($this->is_my_employee($visit->employee_id == false)) {
        //     abort(403, 'Permission Denaid');
        // }
        $convayances = Convayance::where('visit_id', $visit->id)->first();
        if (!$convayances) {
            $convayances = new Convayance;
            $convayances->visit_plan_id = $visit->visit_plan->id;
            $convayances->visit_id = $visit->id;
            $convayances->employee_id = $visit->employee_id;
            $convayances->customer_id = $visit->customer_id;
            $convayances->status = 'temp';
            $convayances->addedBy_id = Auth::id();
            $convayances->save();
        }
        return view('employee.visitPlan.visit.convayances.convayances', compact('convayances', 'visit'));
    }
    public function convayancesAdd(Request $request)
    {
        $convayances = Convayance::find($request->convayances);
        $visit = Visit::find($request->visit);
        $convayances_item = new ConvayanceItems;
        $convayances_item->convayance_id = $convayances->id;
        $convayances_item->amount = $request->amount;
        $convayances_item->start_time = $request->start_time;
        $convayances_item->end_time = $request->end_time;
        $convayances_item->start_from = $request->start_from;
        $convayances_item->start_to = $request->start_to;
        $convayances_item->travel_mode = $request->travel_mode;
        $convayances_item->movement_details = $request->movement_details;
        $convayances_item->addedBy_id = Auth::id();
        $convayances_item->save();

        $total_amount = $convayances->items()->sum('amount');
        $convayances->total_amount = $total_amount;
        $convayances->save();

        return response()->json([
            'success' => true,
            'total_amount' => $total_amount,
            'html' => view('employee.visitPlan.visit.convayances.ajax.covayancesList', compact('convayances', 'convayances_item', 'visit'))->render(),
        ]);
    }
    public function convayancesDelete(Request $request)
    {
        $convayance = Convayance::find($request->convayances);
        if ($convayance->status == 'approved') {
            return response()->json([
                'success' => false,
                'messge' => "You are not able to delete"
            ]);
        }
        $convayance_item = ConvayanceItems::find($request->item);
        $convayance_item->delete();

        $total_amount = $convayance->items()->sum('amount');
        $convayance->total_amount = $total_amount;
        $convayance->save();

        return response()->json([
            'success' => true,
            'total_amount' => $convayance->total_amount
        ]);
    }
    public function convayancesChangeAjax(Request $request)
    {
        $convayance = Convayance::find($request->convayances);
        $convayance_item = ConvayanceItems::find($request->item);
        $type = $request->type;
        $value =$request->value;

        if ($type == 'movement_details') {
            $convayance_item->movement_details = $value;
            $convayance_item->editedBy_id = Auth::id();
            $convayance_item->save();
        } elseif ($type == 'travel_mode') {
            $convayance_item->travel_mode = $value;
            $convayance_item->editedBy_id = Auth::id();
            $convayance_item->save();
        } elseif ($type == 'start_time') {
            $convayance_item->start_time = $value;
            $convayance_item->editedBy_id = Auth::id();
            $convayance_item->save();
        } elseif ($type == 'end_time') {
            $convayance_item->end_time = $value;
            $convayance_item->editedBy_id = Auth::id();
            $convayance_item->save();
        } elseif ($type == 'start_from') {
            $convayance_item->start_from = $value;
            $convayance_item->editedBy_id = Auth::id();
            $convayance_item->save();
        } elseif ($type == 'start_to') {
            $convayance_item->start_to = $value;
            $convayance_item->editedBy_id = Auth::id();
            $convayance_item->save();
        } elseif ($type == 'amount') {
            $convayance_item->amount = $value;
            $convayance_item->editedBy_id = Auth::id();
            $convayance_item->save();
            $total_amount = $convayance->items()->sum('amount');
            $convayance->total_amount = $total_amount;
            $convayance->save();
        }

        return response()->json([
            'success' => true,
            'total_amount' => $convayance->total_amount
        ]);
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
    public function emplyeeDetailsAboutMovement(Request $request, Visit $visit)
    {
        if (!Auth::user()->employee->team_admin) {
            abort(403);
        }
        $type = $request->type;
        $employee = $visit->employee;
        if ($type == 'location') {
            $locations = UserLocation::with('Office')
                ->where('user_id', $employee->user->id)
                ->where('office_location_id', "<>", null)
                ->whereDate('date', Carbon::parse($visit->visit_plan->date_time)->format('Y-m-d'))
                ->latest()
                ->paginate(100);
            return view('employee.visitPlan.visit.convayances.location', compact('locations', 'employee', 'visit'));
        } elseif ($type == 'visit') {

            $office_visit_locations = UserLocation::where('user_id', $employee->user_id)
                ->whereDate('date', Carbon::parse($visit->visit_plan->date_time)->format('Y-m-d'))
                ->where('office_location_id', '!=', null)
                ->orderBy('created_at')
                ->get();

            return view('employee.visitPlan.visit.convayances.officeVisitsList', compact('office_visit_locations', 'employee', 'visit'));
        } else {
            return back();
        }
    }

    public function allConvayances(Request $request)
    {
        $type = $request->type;
        menuSubmenu('allConvayances', 'CB_' . $type);
        $employee = Auth::user()->employee;
        if ($employee->team_admin && ($employee->company->access_all_call_visit_plan_without_call || $employee->company->account_maintain_permission)) {
            $convayance_bills = Convayance::where('status', $type)->where('status', '!=', null)->latest()->paginate(100);
        } else {
            if ($type == 'paid') {
                $convayance_bills = Convayance::whereIn('employee_id', $this->alEmployees())->where('paid', true)->where('status', '!=', null)->latest()->paginate(100);
            } else {
                $convayance_bills = Convayance::whereIn('employee_id', $this->alEmployees())->where('paid', false)->where('status', $type)->where('status', '!=', null)->latest()->paginate(100);
            }
        }
        return view('employee.convayances.convances', compact('convayance_bills', 'type'));
    }
    public function convayancesDetails(Request $request, Convayance $convayance)
    {
        $convayance_items = ConvayanceItems::where('convayance_id', $convayance->id)->get();
        return view('employee.convayances.convancesDetails', compact('convayance', 'convayance_items'));
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
        return view('employee.convayances.convancesPayment', compact('convayance_bills'));
    }

    public function convayancesBillPaymentDetails(Request $request, Convayance $convayance)
    {
        $convayance_items = ConvayanceItems::where('convayance_id', $convayance->id)->get();
        return view('employee.convayances.convancesPaymentDetails', compact('convayance', 'convayance_items'));
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
}
