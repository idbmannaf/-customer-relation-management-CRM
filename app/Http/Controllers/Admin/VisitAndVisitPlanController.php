<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CollectionHistory;
use App\Models\Convayance;
use App\Models\ConvayanceItems;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\GlobalImage;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\OfficeLocation;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SalesItems;
use App\Models\ServiceMaintaince;
use App\Models\ServiceProduct;
use App\Models\ServiceRequirementsofbatteryAndSpare;
use App\Models\UserLocation;
use App\Models\Visit;
use App\Models\VisitPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class VisitAndVisitPlanController extends Controller
{
    public function visitPlans(Request $request)
    {
        $status = $request->status;
        menuSubmenu('visitPlans', "visitPlan");
        $visit_plans = VisitPlan::orderBy('date_time', 'DESC')->where('status', $status)->paginate(20);
        return view('admin.visitPlan.visitPlan', compact('status', 'visit_plans'));
    }
    public function visitPlanCreate(Request $request)
    {
        $customers = Customer::paginate(50);
        $employees = Employee::where('active', true)->get();
        return view('admin.visitPlan.createVisitPlan', compact('customers', 'employees'));
    }
    public function visitPlanEmployeeToCustomers(Request $request)
    {
        $customers = Customer::where('employee_id', $request->employee)
            ->select(['id', 'customer_name', 'customer_code'])
            ->get();
        if ($customers->count() > 0) {
            return response()->json([
                'success' => true,
                'html' => view('admin.visitPlan.ajax.customersOption', compact('customers'))->render(),
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }
    public function visitPlanCustomersToOffice(Request $request)
    {
        $customers = Customer::find($request->customer);
        $customer_offices = OfficeLocation::where('customer_company_id', $customers->company_id)
            ->where('type', 'customer')
            ->get();

        if ($customer_offices->count() > 0) {
            return response()->json([
                'success' => true,
                'html' => view('admin.visitPlan.ajax.customersCompanyOfficeOption', compact('customer_offices'))->render(),
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }

    public function visitPlanStore(Request $request)
    {

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
        if ($request->service_type == 'service') {
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
    public function visitPlanEdit(Request $request, VisitPlan $visitPlan)
    {
        if ($request->isMethod('get')) {
            $customerOfficeLocations = OfficeLocation::where('customer_company_id', $visitPlan->customer->company_id)->get();
            $customers = Customer::where('employee_id', $visitPlan->employee_id)->get();
            $employees = Employee::where('active', true)->get();
            return view('admin.visitPlan.editVisitPlan', compact('customers', 'visitPlan', 'customerOfficeLocations', 'employees'));
        }
        if ($request->isMethod('patch')) {
            $request->validate([
                'date' => 'required|date',
                'time' => 'required',
                'employee' => 'required',
                'customer' => 'required',
                'payment_collection_date' => 'nullable',
                'payment_maturity_date' => 'nullable',
                'purpose_of_visit' => 'required|string',
                'customer_address' => 'required',
            ]);
            $employee = Employee::find($request->employee);

            $date_time = $request->date . " " . $request->time . ":00";
            $visitPlan->employee_id = $employee->id;
            $visitPlan->customer_id = $request->customer;
            $visitPlan->date_time = $date_time;
            $visitPlan->customer_id = $request->customer;
            $visitPlan->purpose_of_visit = $request->purpose_of_visit;
            $visitPlan->payment_collection_date = $request->payment_collection_date;
            $visitPlan->payment_maturity_date = $request->payment_maturity_date;
            if (is_numeric($request->customer_address)) {
                $visitPlan->customer_address = OfficeLocation::find($request->customer_address)->title;
            } else {
                $visitPlan->customer_address = $request->customer_address;
            }
            $visitPlan->type = $request->type;
            $visitPlan->editedBy_id = Auth::id();
            if ($request->approved) {
                $visitPlan->status = 'approved';
                $visitPlan->team_admin_approved_at = now();
            } else {
                $visitPlan->status = 'pending';
            }

            $visitPlan->save();

            return redirect()->back()->with('success', "{$visitPlan->type} Visit Plan Updated Successfuly");
        }
    }

    public function visits(Request $request, VisitPlan $visitPlan)
    {

        $visit_plan = $visitPlan;
        $visits = Visit::where('visit_plan_id', $visitPlan->id)->with('customer')->latest()->get();
        return view('admin.visitPlan.visit.visit', compact('visit_plan', 'visits'));

        // if ($request->isMethod('get')) {
        //     $visits = Visit::where('visit_plan_id', $visitPlan->id)->with('customer')->latest()->get();
        //     return view('admin.visitPlan.visit.visit', compact('visitPlan', 'visits'));
        // }
        // if ($request->isMethod('post')) {
        //     return view('admin.visitPlan.visit.visitAdd', compact('visitPlan'));
        // }
    }

    public function customerVisited(Request $request, VisitPlan $visitPlan)
    {
        if ($visitPlan->customer_office_location_id) {
            return redirect()->back()->with('warning', 'You Already Visited');
        } else {
            $user_location = UserLocation::where('user_id', Auth::id())->latest()->first();
            if (!$user_location) {
                return redirect()->back()->with('warning', 'Trun On Your Location');
            }

            $radius = .2;
            $haversine = "(6371 * acos(cos(radians(" . $user_location->lat . "))
                                    * cos(radians(`lat`))
                                    * cos(radians(`lng`)
                                    - radians(" . $user_location->lng . "))
                                    + sin(radians(" . $user_location->lat . "))
                                    * sin(radians(`lat`))))";

            $customer_office_location = OfficeLocation::select('id', 'lat', 'lng', 'title', 'type')
                ->where('active', true)
                ->where('customer_company_id', $visitPlan->customer->company_id)
                ->whereRaw("{$haversine} < ?", [$radius])
                ->selectRaw("{$haversine} AS distance")
                ->latest()
                ->orderBy('distance')
                ->first();


            if (!$customer_office_location) {
                $customer_office_location = new OfficeLocation;
                $customer_office_location->title = $visitPlan->service_address;
                $customer_office_location->customer_company_id = $visitPlan->customer->company_id;
                $customer_office_location->lat = $user_location->lat;
                $customer_office_location->lng = $user_location->lng;
                $customer_office_location->type = 'customer';
                $customer_office_location->active = true;
                $customer_office_location->addedBy_id = Auth::id();
                $customer_office_location->save();
            }

            $visitPlan->customer_office_location_id = $customer_office_location->id;
            $visitPlan->visit_start_at = now();
            $visitPlan->save();
            return redirect()->back()->with('success', 'Thanks For Visit');
        }
    }

    public function visitAdd(Request $request, VisitPlan $visitPlan)
    {
        $visit_plan = $visitPlan;
        if ($request->isMethod('get')) {

            $visit = Visit::where('visit_plan_id', $visit_plan->id)->first();
            $sibling_visit_id = null;
            if ($visit) {
                $sibling_visit_id = $visit->id;
            }

            $sales_items = SalesItems::where('visit_plan_id', $visit_plan->id)->get();

            $service_products = ServiceProduct::where('visit_plan_id', $visit_plan->id)->where('visit_id', null)->get();
            $complain = Visit::latest()->first();
            if ($complain) {
                $complain_no = $complain->id + 1;
            } else {
                $complain_no = 1;
            }

            $attachments = GlobalImage::where('visit_plan_id', $visit_plan->id)->where('type', 'visit')->where('visit_id', null)->get();
            $product_categories = ProductCategory::whereHas('products')->where('type', 'products')->orderBy('name')->get();
            $invoice = null;
            if ($visit_plan->invoice_id) {
                $invoice = Invoice::find($visit_plan->invoice_id);
            }

            return view('admin.visitPlan.visit.visitAdd', compact('visit_plan', 'sales_items', 'service_products', 'complain_no', 'attachments', 'product_categories', 'sibling_visit_id', 'invoice'));
        }
        if ($request->isMethod('post')) {

            if ($visit_plan->service_type == 'service') {
                $request->validate([
                    'call_description.*' => 'required',
                    'call_description' => 'required',
                    'call_status' => 'required',
                ]);
                if ($request->call_status != 'done') {
                    if (!$visit_plan->hasRequirementBatteryAndSpearpart()) {
                        return redirect()->back()->with('warning', 'Select Required Spear parts/battery ');
                    }
                }
            }


            if ($visit_plan->service_type == 'collection') {
                $request->validate([
                    'collection_amount' => 'required',
                    'payment_method' => 'required',
                ]);
                if ($request->payment_method == 'cash') {
                    $request->validate([
                        'payment_collection_date' => 'required|date'
                    ]);
                }
                if ($request->payment_method == 'cheque') {
                    $request->validate([
                        'payment_maturity_date' => 'required|date',
                        'check_number' => 'required'
                    ]);
                }
                if ($visit_plan->payment_maturity_date && ($visit_plan->payment_collection_amount < $request->collection_amount)) {
                    return redirect()->back()->with('warning', "Collection Amount must be {$visit_plan->payment_collection_amount}. Because, This Collection came from Payment Maturity Date");
                }
            }
            //Validation End

            $visit = new Visit;
            $visit->visit_plan_id = $visit_plan->id;
            $visit->sibling_visit_id = $request->sibling_visit_id ?? null;
            $visit->employee_id = $visitPlan->employee_id;
            $visit->customer_id = $visit_plan->customer_id;
            $visit->purpose_of_visit = $request->purpose_of_visit;
            $visit->remarks = $request->remarks;
            $visit->sale_amount = $paid_amount ?? 0.00;
            $visit->addedBy_id = Auth::id();
            $visit->status = 'pending';
            $visit->save();

            if ($visit_plan->service_type == 'service') {
                $visit->call_description = implode(',', $request->call_description);
                $visit->ventilation_system = $request->ventilation_system ? 1 : 0;
                $visit->air_condition = $request->air_condition ? 1 : 0;
                $visit->room_temperature = $request->room_temperature;
                $visit->ac_temperature = $request->ac_temperature;
                $visit->ac_run_time = $request->ac_run_time;
                $visit->other = $request->other;
                $visit->imput_vat = $request->imput_vat;
                $visit->charging_v = $request->charging_v;
                $visit->charging_amp = $request->charging_amp;
                $visit->imput_freq = $request->imput_freq;
                $visit->earthen_voltage = $request->earthen_voltage;
                $visit->output_vac = $request->output_vac;
                $visit->output_freq = $request->output_freq;
                $visit->earthen_voltage_le = $request->earthen_voltage_le;
                $visit->earthen_voltage_ne = $request->earthen_voltage_ne;
                $visit->battery_brand = $request->battery_brand;
                $visit->battery_amp = $request->battery_amp;
                $visit->battery_qty = $request->battery_qty;
                $visit->battery_time = $request->battery_time;
                $visit->physical_condition = $request->physical_condition;
                $visit->other_one = $request->other_one;
                $visit->other_two = $request->other_two;
                $visit->mobile_number = $request->mobile_number;
                $visit->ft_aym_no = $request->ft_aym_no;

                $date_time = $request->date . " " . $request->time . ":00";
                $visit->date_time = $date_time;

                $visit->save();

                // if ($request->motherboard_checked || $request->motherboard_cleaned || $request->ac_dc_cable_checked || $request->ac_dc_cable_cleaned || $request->breaker_checked || $request->breaker_cleaned || $request->socket_checked || $request->socket_cleaned || $request->battery_ernubak_checked || $request->battery_ernubak_cleaned) {
                $service_maintaince = new ServiceMaintaince;
                $service_maintaince->visit_plan_id = $visit_plan->id;
                $service_maintaince->visit_id = $visit->id;
                $service_maintaince->motherboard_checked = $request->motherboard_checked ? 1 : 0;
                $service_maintaince->motherboard_cleaned = $request->motherboard_cleaned ? 1 : 0;
                $service_maintaince->ac_dc_cable_checked = $request->ac_dc_able_checked ? 1 : 0;
                $service_maintaince->ac_dc_cable_cleaned = $request->ac_dc_able_cleaned ? 1 : 0;
                $service_maintaince->breaker_checked = $request->breaker_checked ? 1 : 0;
                $service_maintaince->battery_ernubak_checked = $request->battery_ernubak_checked ? 1 : 0;
                $service_maintaince->battery_ernubak_cleaned = $request->battery_ernubak_cleaned ? 1 : 0;
                $service_maintaince->socket_checked = $request->socket_checked ? 1 : 0;
                $service_maintaince->socket_cleaned = $request->socket_cleaned ? 1 : 0;
                $service_maintaince->save();
                // }

                $visit->call_status = $request->call_status;

                if ($request->call_status == 'pending') {
                    $visit->call_pending_at = now();
                } elseif ($request->call_status == 'reviewed') {
                    $visit->call_reviewed_at = now();
                } elseif ($request->call_status == 'approved') {
                    $visit->call_approved_at = now();
                } elseif ($request->call_status == 'done') {
                    $visit->done_at = now();
                    $visit->done_by = Auth::id();
                    // if ($visit->service_charge) {
                    //     $visit->service_charge = $request->service_charge;
                    // }
                } elseif ($request->call_status == 'completed') {
                    $visit->call_completed_at = now();
                }
                $visit->remarks = $request->remarks;
                $visit->eng_signature = $request->eng_signature;
                $visit->eng_date = $request->eng_date;
                $visit->customer_signature = $request->customer_signature;
                $visit->customer_date = $request->customer_date;
                $visit->seal = $request->seal;
                $visit->save();
                $visit_plan->serviceProducts()->update([
                    'visit_id' => $visit->id
                ]);
                $visit_plan->service_requirment_batt_spear_parts()->update([
                    'visit_id' => $visit->id
                ]);
            }

            // if (has_in_array($visit_plan->service_type, 'sales')) {
            //     $visit_plan->sales_items()->update([
            //         'visit_id' => $visit->id
            //     ]);
            //     $visit->sale_amount = $visit_plan->total_sales_price();
            //     $visit->save();
            // }

            if ($visit_plan->service_type == 'collection') {
                if ($request->invoice) {
                    $visit->next_paymnet_collection_date = $request->next_collection_date ?? null;
                    $visit->invoice_id = $request->invoice;
                }
                $visit->payment_method = $request->payment_method;
                $visit->check_number = $request->check_number ?? null;
                $visit->collection_amount = $request->collection_amount;
                $visit->collection_details = $request->collection_details;
                $visit->payment_collection_date = $request->payment_collection_date ?? null;
                $visit->payment_maturity_date = $request->payment_maturity_date  ?? null;
                $visit->save();
            }


            if (count($visit_plan->files) > 0) {
                $visit_plan->files()->update(['visit_id' => $visit->id]);
            }
            if (!$visit->date_time) {
                $visit->date_time = now();
                $visit->save();
            }

            $visit_plan->visited_at = now();
            $visit_plan->visit_start_at = now();
            $visit_plan->save();


            return redirect()->route('admin.visit.add', $visitPlan)->with('success', 'Visit Created Successfully');
        }
    }
    public function visitEdit(Request $request, Visit $visit)
    {
        if ($request->isMethod('get')) {
            $visit_plan = $visit->visit_plan;
            $sales_items = SalesItems::where('visit_plan_id', $visit_plan->id)->get();
            return view('admin.visitPlan.visit.editVisit', compact('visit', 'visit_plan', 'sales_items'));
        }
        if ($request->isMethod('patch')) {

            $request->validate([
                'date' => 'required|date',
                'time' => 'required',
                // 'payment_collection_date' => 'required|date',
                // 'payment_maturity_date' => 'required|date',
                'achievement' => 'required|string',
                'sale_details' => 'nullable|string',
                'sale_amount' => 'nullable',
                'collection_details' => 'nullable|string',
                'collection_amount' => 'nullable'
            ]);

            $employee = $visit->employee;
            $date_time = $request->date . " " . $request->time . ":00";

            $visit->date_time = $date_time;
            $visit->purpose_of_visit = $request->purpose_of_visit;
            $visit->payment_collection_date = $request->payment_collection_date;
            $visit->payment_maturity_date = $request->payment_maturity_date;
            $visit->achievement = $request->achievement;
            $visit->sale_details = $request->sale_details;
            $visit->sale_amount = $request->sale_amount ?? 0.00;
            $visit->collection_amount = $request->collection_amount ?? 0.00;
            $visit->collection_details = $request->collection_details;
            $visit->editedBy_id = Auth::id();
            $visit->status = 'pending';
            $visit->save();
            return redirect()->back()->with('success', 'Visit Updated Successfully');
        }
    }
    public function visitUpdateStatus(Request $request, Visit $visit)
    {
        if ($visit->status == $request->status) {
            return back();
        }

        if ($request->status == 'approved') {
            $customer = $visit->customer;
            if ($visit->sale_amount) {
                $visit->prev_sale_amount = $customer->ledger_balance;
                $visit->moved_sale_amount = $visit->sale_amount;
                $visit->current_sale_ledger_balance = $visit->prev_sale_amount + $visit->moved_sale_amount;
                $customer->increment('ledger_balance', $visit->sale_amount);
            }
            if ($visit->collection_amount) {
                $visit->prev_collection_amount = $customer->ledger_balance;
                $visit->moved_collection_amount = $visit->collection_amount;
                $visit->current_collection_ledger_balance = $visit->prev_collection_amount - $visit->moved_collection_amount;
                $customer->decrement('ledger_balance', $visit->collection_amount);
            }

            $visit->current_ledger_balance = $customer->ledger_balance;
            $visit->note = "{$visit->sale_amount} taka added and {$visit->collection_amount} diducted from customer ladger balance";
            $visit->status = 'approved';
            $visit->approved_at = now();
            $visit->save();
        }
        if ($request->status == 'rejected') {
            $visit->status = 'rejected';
            $visit->rejected_at = now();
            $visit->save();
        }
        return redirect()->back()->with('success', "Visit Status Updated to {$visit->status}");
    }


    public function convayances(Request $request, Visit $visit)
    {

        $convayance = Convayance::where('visit_id', $visit->id)->first();
        if (!$convayance) {
            $convayance = new Convayance;
            $convayance->visit_plan_id = $visit->visit_plan->id;
            $convayance->visit_id = $visit->id;
            $convayance->employee_id = $visit->employee_id;
            $convayance->customer_id = $visit->customer_id;
            $convayance->status = 'temp';
            $convayance->addedBy_id = Auth::id();
            $convayance->save();
        }
        return view('admin.visitPlan.visit.convayances.convayances', compact('convayance', 'visit'));
    }
    public function convayancesAdd(Request $request)
    {
        $convayances = Convayance::find($request->convayance);
        $visit = Visit::find($request->visit);
        $convayances_item = new ConvayanceItems;
        $convayances_item->convayance_id = $convayances->id;
        $convayances_item->movement_details = $request->movement_details;
        $convayances_item->amount = $request->amount;
        $convayances_item->start_time = $request->start_time;
        $convayances_item->end_time = $request->end_time;
        $convayances_item->start_from = $request->start_from;
        $convayances_item->start_to = $request->start_to;
        $convayances_item->travel_mode = $request->travel_mode;
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
        $convayance = Convayance::find($request->convayance);
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
        $convayance = Convayance::find($request->convayance);
        $convayance_item = ConvayanceItems::find($request->item);
        $type = $request->type;
        $value = $request->value;

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

    public function convayancesStore(Request $request, Visit $visit, Convayance $convayance)
    {
        $request->validate([
            'status' => 'required'
        ]);

        $type = $request->status;
        if ($type == 'pending') {
            $convayance->status = 'pending';
            $convayance->editedBy_id = Auth::id();
            $convayance->save();
        } elseif ($type == 'rejected') {
            $convayance->status = 'rejected';
            $convayance->rejected_at = now();
            $convayance->editedBy_id = Auth::id();
            $convayance->save();
        } elseif ($type == 'approved') {
            $convayance->status = 'approved';
            $convayance->approved_at = now();
            $convayance->editedBy_id = Auth::id();
            $convayance->save();
            //Need Transiction History for Employee
        }
        return redirect()->back()->with('success', 'Convayances Bill Claim Successfully Updated');
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
            return view('admin.visitPlan.visit.convayances.location', compact('locations', 'employee', 'visit'));
        } elseif ($type == 'visit') {

            $office_visit_locations = UserLocation::where('user_id', $employee->user_id)
                ->whereDate('date', Carbon::parse($visit->date_time)->format('Y-m-d'))
                ->where('office_location_id', '!=', null)
                ->orderBy('created_at')
                ->get();

            return view('admin.visitPlan.visit.convayances.officeVisitsList', compact('office_visit_locations', 'employee', 'visit'));
        } else {
            return back();
        }
    }

    public function allVisits(Request $request)
    {
        $status = $request->status;
        $sibling_visit_id = $request->sibling ?? null;
        $type = $request->type;
        menuSubmenu('visits', "visit_" . $type);
        if ($type == 'today') {
            $visits = Visit::whereDate('date_time', Carbon::now())
                ->orderBy('date_time', 'DESC')
                ->paginate(100);
        } elseif ($type == 'pending') {
            $visits = Visit::where('status', 'pending')
                ->orderBy('date_time', 'DESC')
                ->paginate(100);
        } elseif ($type == 'approved') {
            $visits = Visit::where(function ($q) use ($type) {
                if ($type) {
                    $q->where('status', $type);
                }
            })
                ->orderBy('date_time', 'DESC')
                ->paginate(100);
        } elseif ($type == 'completed') {
            $visits = Visit::where('status', 'completed')
                ->orderBy('date_time', 'DESC')
                ->paginate(100);
        } else {
            $visits = Visit::where(function ($q) use ($status) {
                if ($status) {
                    $q->where('status', $status);
                }
            })
                ->paginate(100);
        }

        // return view('employee.visitPlan.visit.allOfMyTeamMemberVisits', compact('visits', 'type', 'status'));
        return view('admin.visitPlan.visit.typeWiseVisits', compact('visits', 'type', 'status'));
    }
    public function visitSales(Request $request)
    {
        menuSubmenu('saleNcollection', 'allSales');
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
        if ($request->employee) {
            $employee = Employee::find($request->employee);
        } else {
            $employee = null;
        }
        // dd( $employee);

        return view('admin.visitPlan.saleAndCollection.visitSale', compact('visit_sales', 'input', 'employee'));
    }
    public function visitCollection(Request $request, Employee $employee)
    {
        menuSubmenu('saleNcollection', 'allCollections');
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
        if ($request->employee) {
            $employee = Employee::find($request->employee);
        } else {
            $employee = null;
        }
        return view('admin.visitPlan.saleAndCollection.visitCollection', compact('visit_collection', 'input', 'employee'));
    }
    public function tempSalesItemAjax(Request $request)
    {
        $product = Product::find($request->product_id);
        $visit_plan = VisitPlan::find($request->visit_plan);

        $sales_item = SalesItems::where('product_id', $request->product_id)->where('visit_plan_id', $request->visit_plan)->first();
        if ($sales_item) {
            return response()->json([
                'success' => false,
                'msg' => "This Product Already Added"
            ]);
        } else {
            $sales_item = new SalesItems;
            $sales_item->employee_id = $visit_plan->employee_id;
            $sales_item->visit_plan_id = $visit_plan->id;
            $sales_item->product_id = $product->id;
            $sales_item->product_name = $product->name;
            $sales_item->product_name = $product->name;
            $sales_item->product_warranty = $product->warranty;
            $sales_item->product_capacity = $product->capacity;
            $sales_item->product_backup_time = $product->backup_time;
            $sales_item->product_unit_price = $product->unit_price;
            $sales_item->product_quantity = 1;
            $sales_item->product_final_price = $sales_item->product_quantity * $sales_item->product_unit_price;
            $sales_item->addedBy_id = Auth::id();
            $sales_item->save();
            return response()->json([
                'success' => true,
                'sale_amount' => $visit_plan->total_sales_price(),
                'html' => view('employee.visitPlan.ajax.salesitem', compact('sales_item', 'visit_plan'))->render()
            ]);
        }
    }
    public function tempSalesItemDeleteAjax(Request $request)
    {
        $visit_plan = VisitPlan::find($request->visit_plan);
        $sales_item = SalesItems::find($request->item);
        $sales_item->delete();
        return response()->json([
            'success' => true,
            'sale_amount' => $visit_plan->total_sales_price(),
        ]);
    }
    public function tempSalesItemUpdateAjax(Request $request)
    {
        $visit_plan = VisitPlan::find($request->visit_plan);
        $sales_item = SalesItems::find($request->item);
        $type = $request->type;
        if ($type == 'name') {
            $sales_item->product_name = $request->value;
        }
        if ($type == 'warranty') {
            $sales_item->product_warranty = $request->value;
        }
        if ($type == 'capacity') {

            $sales_item->product_capacity = $request->value;
        }
        if ($type == 'backup_time') {
            $sales_item->product_backup_time = $request->value;
        }
        if ($type == 'quantity') {
            $sales_item->product_quantity = $request->value;
            $sales_item->product_final_price = $request->value * $sales_item->product_unit_price;
            $sales_item->editedBy_id = Auth::id();
            $sales_item->save();
            return response()->json([
                'success' => true,
                'sale_amount' => $visit_plan->total_sales_price(),
            ]);
        }
        if ($type == 'unit_price') {
            $sales_item->product_unit_price = $request->value;
            $sales_item->product_final_price = $sales_item->product_quantity * $sales_item->product_unit_price;
            $sales_item->editedBy_id = Auth::id();
            $sales_item->save();
            return response()->json([
                'success' => true,
                'unit_price' => $visit_plan->product_unit_price,
                'sale_amount' => $visit_plan->total_sales_price(),
            ]);
        }

        $sales_item->editedBy_id = Auth::id();
        $sales_item->save();
        return response()->json([
            'success' => true,
        ]);
    }

    public function customerVisitStatusUpdate(Request $request, VisitPlan $visit_plan, Visit $visit)
    {

        if ($visit->status == $request->status) {
            return back();
        }
        if ($request->status == 'confirmed') {
            $visit->status = 'confirmed';
            $visit->save();
        }
        if ($request->status == 'approved') {
            $customer = $visit->customer;
            if ($visit->collection_amount) {
                if (!$visit->payment_maturity_date) {
                    if ($visit_plan->invoice) {
                        $total_invoice_amount = $visit_plan->invoice->due > 0 ? $visit_plan->invoice->due : $visit_plan->invoice->total_amount;
                        if ($visit->collection_amount < $total_invoice_amount) {
                            $due = $total_invoice_amount - $visit->collection_amount;
                            if ($visit->next_paymnet_collection_date) {
                                $new_visit_plan = new VisitPlan;
                                $new_visit_plan->service_type = $visit_plan->service_type;
                                $new_visit_plan->employee_id = $visit_plan->employee_id;
                                $new_visit_plan->customer_id = $visit_plan->customer_id;
                                $new_visit_plan->date_time = $visit->next_paymnet_collection_date . " 00:00:00";
                                $new_visit_plan->purpose_of_visit = "{$due} Taka collection from Customer Ledger Balance for invoice {$visit_plan->invoice->id}.";
                                $new_visit_plan->payment_collection_date = $visit->next_paymnet_collection_date ?? null;
                                $new_visit_plan->payment_maturity_date = null;
                                $new_visit_plan->customer_address = $visit_plan->customer_address;
                                $new_visit_plan->status = 'approved';
                                $new_visit_plan->team_admin_approved_at = now();
                                $new_visit_plan->invoice_id = $visit->visit_plan->invoice->id;
                                $new_visit_plan->addedBy_id = Auth::id();
                                $new_visit_plan->save();

                                $collection_history = new CollectionHistory;
                                $collection_history->invoice_id = $visit_plan->invoice_id;
                                $collection_history->visit_plan_id = $visit_plan->id;
                                $collection_history->visit_id = $visit->id;
                                $collection_history->customer_id = $visit_plan->customer_id;
                                $collection_history->collection_by = $visit_plan->employee_id;
                                $collection_history->collection_approved_by = Auth::id();
                                $collection_history->payment_method = $visit->payment_method;
                                $collection_history->prev_due = $customer->ledger_balance;
                                $collection_history->moved_amount = $visit->collection_amount;
                                $collection_history->current_due = $collection_history->prev_due - $collection_history->moved_amount;
                                $collection_history->purpose = 'minus';
                                $collection_history->note = "{ $collection_history->moved_amount} Taka diducted from Customer Ledger Balance for Invoice ID ({$visit_plan->invoice_id})";
                                $collection_history->save();
                                $customer->decrement('ledger_balance', $collection_history->moved_amount);
                                $visit_plan->invoice()->update(
                                    [
                                        'due' => $due,
                                        'payment_status' => 'partial'
                                    ]
                                );
                            } else {
                                $visit->prev_collection_amount = $customer->ledger_balance;
                                $visit->moved_collection_amount = $visit->collection_amount;
                                $visit->current_collection_ledger_balance = ($visit->prev_collection_amount - $visit->moved_collection_amount); //+ $due

                                $visit->note = "employee Went for {$total_invoice_amount} Taka Collection form Invoice {$visit_plan->invoice->id} but Customer Paid {$visit->moved_collection_amount} and Due {$due} Taka Auto added in Customer Ledger Balance.";
                                $visit->save();

                                $collection_history = new CollectionHistory;
                                $collection_history->invoice_id = $visit_plan->invoice_id;
                                $collection_history->visit_plan_id = $visit_plan->id;
                                $collection_history->visit_id = $visit->id;
                                $collection_history->customer_id = $visit_plan->customer_id;
                                $collection_history->collection_by = $visit_plan->employee_id;
                                $collection_history->collection_approved_by = Auth::id();
                                $collection_history->payment_method = $visit->payment_method;
                                $collection_history->prev_due = $customer->ledger_balance;
                                $collection_history->moved_amount =  $visit->collection_amount;
                                $collection_history->current_due = $collection_history->prev_due - $collection_history->moved_amount;
                                $collection_history->purpose = 'minus';
                                $collection_history->note = "{ $collection_history->moved_amount} Taka diducted from Customer Ledger Balance for Invoice ID ({$visit_plan->invoice_id})";
                                $collection_history->save();

                                $customer->decrement('ledger_balance', $collection_history->moved_amount);

                                $visit_plan->invoice()->update(
                                    [
                                        'due' => 0.00,
                                        'payment_status' => 'paid'
                                    ]
                                );
                            }
                        } elseif ($visit->collection_amount > $total_invoice_amount) {
                            $advance = $visit->collection_amount - $total_invoice_amount;
                            $visit->prev_collection_amount = $customer->ledger_balance;
                            $visit->moved_collection_amount = $visit->collection_amount;
                            $visit->current_collection_ledger_balance = ($visit->prev_collection_amount - $visit->moved_collection_amount); //+ $due

                            $visit->note = "employee Went for {$total_invoice_amount} Taka Collection form Invoice {$visit_plan->invoice->id} but Customer Paid {$visit->moved_collection_amount} Taka and Advance {$advance} Taka Auto minus from Customer Ledger Balance.";
                            $visit->save();

                            $collection_history = new CollectionHistory;
                            $collection_history->invoice_id = $visit_plan->invoice_id;
                            $collection_history->visit_plan_id = $visit_plan->id;
                            $collection_history->visit_id = $visit->id;
                            $collection_history->customer_id = $visit_plan->customer_id;
                            $collection_history->collection_by = $visit_plan->employee_id;
                            $collection_history->collection_approved_by = Auth::id();
                            $collection_history->payment_method = $visit->payment_method;
                            $collection_history->prev_due = $customer->ledger_balance;
                            $collection_history->moved_amount = $visit->collection_amount - $advance;
                            $collection_history->current_due = $collection_history->prev_due - $collection_history->moved_amount;
                            $collection_history->purpose = 'minus';
                            $collection_history->note = "{ $collection_history->moved_amount} Taka diducted from Customer Ledger Balance for Invoice ID ({$visit_plan->invoice_id})";
                            $collection_history->save();

                            $customer->decrement('ledger_balance', $collection_history->moved_amount);

                            $collection_history = new CollectionHistory;
                            $collection_history->invoice_id = $visit_plan->invoice_id;
                            $collection_history->visit_plan_id = $visit_plan->id;
                            $collection_history->visit_id = $visit->id;
                            $collection_history->customer_id = $visit_plan->customer_id;
                            $collection_history->collection_by = $visit_plan->employee_id;
                            $collection_history->collection_approved_by = Auth::id();
                            $collection_history->prev_due = $customer->ledger_balance;
                            $collection_history->moved_amount = $advance;
                            $collection_history->current_due = $collection_history->prev_due - $collection_history->moved_amount;
                            $collection_history->purpose = 'minus';
                            $collection_history->note = "{ $collection_history->moved_amount} Taka diducted from Customer Ledger Balance and advance {$advance} Taka . Reffer ....from Invoice ID ({$visit_plan->invoice_id})";
                            $collection_history->save();

                            $customer->decrement('ledger_balance', $collection_history->moved_amount);

                            $visit_plan->invoice()->update(
                                [
                                    'advance' => $advance,
                                    'payment_status' => 'paid'
                                ]
                            );
                        } elseif ($visit->collection_amount == $total_invoice_amount) {
                            $visit->prev_collection_amount = $customer->ledger_balance;
                            $visit->moved_collection_amount = $visit->collection_amount;
                            $visit->current_collection_ledger_balance = ($visit->prev_collection_amount - $visit->moved_collection_amount); //+ $due

                            $visit->note = "employee Went for {$total_invoice_amount} Taka Collection form Invoice {$visit_plan->invoice->id} and  Minus From Customer Ledger Balance.";
                            $visit->save();

                            $collection_history = new CollectionHistory;
                            $collection_history->invoice_id = $visit_plan->invoice_id;
                            $collection_history->visit_plan_id = $visit_plan->id;
                            $collection_history->visit_id = $visit->id;
                            $collection_history->customer_id = $visit_plan->customer_id;
                            $collection_history->collection_by = $visit_plan->employee_id;
                            $collection_history->collection_approved_by = Auth::id();
                            $collection_history->payment_method = $visit->payment_method;
                            $collection_history->prev_due = $customer->ledger_balance;
                            $collection_history->moved_amount =  $visit->collection_amount;
                            $collection_history->current_due = $collection_history->prev_due - $collection_history->moved_amount;
                            $collection_history->purpose = 'minus';
                            $collection_history->note = "{ $collection_history->moved_amount} Taka diducted from Customer Ledger Balance for Invoice ID ({$visit_plan->invoice_id})";
                            $collection_history->save();

                            $customer->decrement('ledger_balance', $collection_history->moved_amount);

                            $visit_plan->invoice()->update(
                                [
                                    'due' => 0.00,
                                    'payment_status' => 'paid'
                                ]
                            );
                        }
                    } else {
                        $visit->prev_collection_amount = $customer->ledger_balance;
                        $visit->moved_collection_amount = $visit->collection_amount;
                        $visit->current_collection_ledger_balance = $visit->prev_collection_amount - $visit->moved_collection_amount;

                        $collection_history = new CollectionHistory;
                        $collection_history->visit_plan_id = $visit_plan->id;
                        $collection_history->visit_id = $visit->id;
                        $collection_history->customer_id = $visit_plan->customer_id;
                        $collection_history->collection_by = $visit_plan->employee_id;
                        $collection_history->collection_approved_by = Auth::id();
                        $collection_history->payment_method = $visit->payment_method;
                        $collection_history->prev_due = $customer->ledger_balance;
                        $collection_history->moved_amount =  $visit->moved_collection_amount;
                        $collection_history->current_due = $collection_history->prev_due - $collection_history->moved_amount;
                        $collection_history->purpose = 'minus';
                        $collection_history->note = "{  $visit->collection_amount} Taka diducted from Customer Ledger Balance";
                        $collection_history->save();

                        $customer->decrement('ledger_balance', $visit->collection_amount);

                        $visit->note = "{$visit->sale_amount} taka added and {$visit->collection_amount} diducted from customer ladger balance";
                        $visit->save();
                    }
                } else {
                    $new_visit_plan = new VisitPlan;
                    $new_visit_plan->service_type = $visit_plan->service_type;
                    $new_visit_plan->employee_id = $visit_plan->employee_id;
                    $new_visit_plan->customer_id = $visit_plan->customer_id;
                    $new_visit_plan->date_time = $visit->next_paymnet_collection_date . " 00:00:00";
                    $new_visit_plan->purpose_of_visit = "{$visit->collection_amount} Taka collection from Cheque Number ({$visit->check_number}) and Payment Maturity Date ($visit->payment_maturity_date) for invoice {$visit_plan->invoice->id}.";
                    // $new_visit_plan->payment_collection_date = $visit->payment_maturity_date ?? null;
                    $new_visit_plan->payment_maturity_date = $visit->payment_maturity_date;
                    $new_visit_plan->payment_collection_amount = $visit->collection_amount;
                    $new_visit_plan->customer_address = $visit_plan->customer_address;
                    $new_visit_plan->status = 'approved';
                    $new_visit_plan->team_admin_approved_at = now();
                    $new_visit_plan->invoice_id = $visit->invoice_id;
                    $new_visit_plan->addedBy_id = Auth::id();
                    $new_visit_plan->save();
                }
            }

            $visit->current_ledger_balance = $customer->ledger_balance;

            if (!$visit->visit_plan->invoice) {
                $visit->note = "{$visit->sale_amount} taka added and {$visit->collection_amount} diducted from customer ladger balance";
            }


            $visit->status = 'approved';
            $visit->approved_at = now();
            $visit->save();
        }
        if ($request->status == 'completed' && $visit_plan->service_type != 'sales') {
            if ($visit->offer_id) {
                if ($visit_plan->service_type == 'sales') {
                    if (!$visit->offer_quotation->customer_approved) {
                        return back()->with('warning', 'Customer Not Approved The Offer Quotation');
                    }
                }
                $invo = Invoice::max('invoice_no');
                $chall = Invoice::max('challan_no');
                if ($invo) {
                    $prepear_inv_number = last(explode('-', $invo)) + 1;
                    $length = 3;
                    $inv_num = substr(str_repeat(0, 3) . $prepear_inv_number, -$length);
                    $invoice_number = 'INV-OCLHO-' . date('Y') . "-" . $inv_num;
                } else {
                    $invoice_number = 'INV-OCLHO-' . date('Y') . "-001";
                }

                if ($chall) {
                    $prepear_chall_number = last(explode('-', $invo)) + 1;
                    $length = 3;
                    $chall_num = substr(str_repeat(0, 3) . $prepear_chall_number, -$length);
                    $challan_number = 'INV-OCLHO-' . date('Y') . "-" . $chall_num;
                } else {
                    $challan_number = 'INV-OCLHO-' . date('Y') . "-001";
                }

                $invoice = new Invoice;
                $invoice->invoice_no = $invoice_number;
                $invoice->challan_no = $challan_number;
                $invoice->employee_id = $visit->employee_id;
                $invoice->customer_id = $visit->customer_id;
                $invoice->visit_plan_id = $visit_plan->id;
                $invoice->visit_id = $visit->id;
                $invoice->offer_id = $visit->offer_id;
                $invoice->invoice_date = now();
                $invoice->s_order_no = $visit->offer_id;
                $invoice->remarks = '';
                $invoice->prepared_by = Auth::id();
                $invoice->buyer_ref_no = $visit->offer_quotation ? $visit->offer_quotation->ref : '';
                $invoice->save();
                $net_amount = 0;

                foreach ($visit->offer_quotation->items as $offer_item) {
                    $invoice_item = new InvoiceItem;
                    $invoice_item->invoice_id = $invoice->id;
                    $invoice_item->employee_id = $visit->employee_id;
                    $invoice_item->customer_id = $visit->customer_id;
                    $invoice_item->customer_offer_item_id = $offer_item->id;
                    $invoice_item->product_id = $offer_item->product_id;
                    $invoice_item->product_name = $offer_item->product_name;
                    $invoice_item->quantity = $offer_item->quantity;
                    $invoice_item->unit_price = $offer_item->unit_price;
                    $invoice_item->total_price = $invoice_item->unit_price * $invoice_item->quantity;
                    $invoice_item->save();
                    $net_amount += $invoice_item->total_price;
                }

                $invoice->status = 'pending';
                $invoice->net_amount = $net_amount ?? 0.00;
                $invoice->vat_amount = 0.00;
                $invoice->service_charge = $visit->offer_quotation->service_charge;
                $invoice->total_amount = $net_amount + $invoice->vat_amount + $invoice->service_charge;
                $invoice->save();

                // make  Invoice End

                $collection_history = new CollectionHistory();
                $collection_history->invoice_id = $invoice->invoice_id;
                $collection_history->visit_plan_id = $invoice->id;
                $collection_history->visit_id = $invoice->visit_id;
                $collection_history->customer_id = $invoice->customer_id;
                $collection_history->collection_by = $invoice->employee_id;
                $collection_history->collection_approved_by = Auth::id();
                $collection_history->prev_due = $visit->customer->ledger_balance;
                $collection_history->moved_amount = $invoice->total_amount;
                $collection_history->current_due = $collection_history->prev_due + $collection_history->moved_amount;
                $collection_history->purpose = 'plus';
                $collection_history->note = "{ $invoice->total_amount } Taka Added in Customer Ledger Balance for Invoice ID ({$invoice->id})";
                $collection_history->save();
                $invoice->customer()->increment('ledger_balance', $invoice->total_amount);
            }
            $visit->status = 'completed';
            $visit->call_status = 'done';
            $visit->completed_by = Auth::id();
            $visit->completed_at = now();

            $visit->save();
            if ($call = $visit_plan->call) {
                $call->done_at = now();
                $call->done_by = Auth::id();
                $call->save();
            }
            $visit_plan->status = 'completed';
            $visit_plan->save();
        }
        if ($request->status == 'rejected') {
            $visit->status = 'rejected';
            $visit->rejected_at = now();
            $visit->save();
        }
        return redirect()->back()->with('success', "Visit Status Updated to {$visit->status}");
    }
    public function ServiceProductAjax(Request $request)
    {
        $product = Product::find($request->product_id);
        $visit_plan = VisitPlan::find($request->visit_plan);

        $service_product_item = ServiceProduct::where('product_id', $request->product_id)->where('visit_plan_id', $request->visit_plan)->first();
        if ($service_product_item) {
            return response()->json([
                'success' => false,
                'msg' => "This Product Already Added"
            ]);
        } else {
            $service_product_item = new ServiceProduct;
            $service_product_item->visit_plan_id = $visit_plan->id;
            $service_product_item->product_id = $product->id;
            $service_product_item->quantity = 1;
            $service_product_item->device_name = $product->name;
            $service_product_item->brand = $product->brand ? $product->brand->name : '';
            $service_product_item->model_no = $product->model;
            $service_product_item->capacity = $product->capacity;
            $service_product_item->addedBy_id = Auth::id();
            $service_product_item->save();
            if ($request->visit_id) {
                $service_product_item->visit_id = $request->visit_id;
                $service_product_item->save();
            }
            return response()->json([
                'success' => true,
                'html' => view('admin.visitPlan.visit.part.sericeProductShow', ['product' => $service_product_item, 'visit_plan' => $visit_plan])->render()
            ]);
        }
    }

    public function serviceProductDeleteAjax(Request $request)
    {
        $service_product = ServiceProduct::find($request->item);
        $service_product->delete();
        return response()->json([
            'success' => true,
        ]);
    }
    public function serviceProductUpdateAjax(Request $request)
    {
        $type = $request->type;
        $service_item = ServiceProduct::find($request->item);
        $service_item->$type = $request->value;
        $service_item->save();
    }

    public function addRequirementsOfBattAndSpearPartAjax(Request $request)
    {
        $visit_plan = VisitPlan::find($request->visit_plan);
        $service_requirment = new ServiceRequirementsofbatteryAndSpare;
        $product = Product::find($request->product_id);

        $service_requirment->visit_plan_id = $visit_plan->id;
        $service_requirment->problem = $request->problem;
        $service_requirment->product_id = $request->product_id;
        $service_requirment->product_type =  $product->product_type;
        $service_requirment->quantity = $request->quantity;
        $service_requirment->unit = $request->unit;
        $service_requirment->addedBy_id = Auth::id();
        if ($request->visit_id) {
            $service_requirment->visit_id = $request->visit_id;
        }

        if ($service_requirment->save()) {
            return response()->json([
                'success' => true,
                'html' => view('employee.visitPlan.visit.ajax.requireServiceAjax', ['visit_plan' => $visit_plan, 'serviceRequirement' => $service_requirment])->render()

            ]);
        }
    }
    public function deleteRequirementsOfBattAndSpearPartAjax(Request $request)
    {
        $service_requirment = ServiceRequirementsofbatteryAndSpare::find($request->item);

        if ($service_requirment->delete()) {
            return response()->json([
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }
    public function updateRequirementsOfBattAndSpearPartAjax(Request $request)
    {

        $type = $request->type;

        $service_item = ServiceRequirementsofbatteryAndSpare::find($request->item);
        $service_item->$type = $request->value;
        if ($type == 'product_id') {
            $product = Product::find($request->value);
            $service_item->product_type = $product->product_type;
        }
        $service_item->save();
    }

    public function customerVisitview(Request $request, VisitPlan $visit_plan, Visit $visit)
    {
        $visit = Visit::where('id', $visit->id)->with('customer', 'employee', 'files', 'serviceProducts', 'service_requirment_batt_spear_parts', 'sales_items', 'service_maintainces')->first();
        $invoice = null;
        if ($visit_plan->invoice) {
            $invoice = $visit_plan->invoice;
        }
        return view('admin.visitPlan.visit.viewVisit', [
            'visit' => $visit,
            'visit_plan' => $visit_plan,
            'invoice' => $invoice,
        ]);
    }
    public function customerVisitEdit(Request $request, VisitPlan $visit_plan, Visit $visit)
    {


        if ($visit->status != 'pending') {
            return redirect()->back()->with('warning', 'You are not Able to Edit This Visit');
        }
        $sales_items = SalesItems::where('visit_plan_id', $visit_plan->id)->get();
        $categories = ProductCategory::whereHas('products')->get();
        $invoice = $visit->invoice;
        return view('admin.visitPlan.visit.editVisit', compact('visit_plan', 'visit', 'sales_items', 'categories', 'invoice'));
    }
    public function customerVisitUpdate(Request $request, VisitPlan $visit_plan, Visit $visit)
    {


        //Validation Start
        if ($visit_plan->service_type == 'service') {
            $request->validate([
                'call_description.*' => 'required',
                'call_description' => 'required',
                'call_status' => 'required',
            ]);
            if ($request->call_status != 'done') {
                if (!$visit_plan->hasRequirementBatteryAndSpearpart()) {
                    return redirect()->back()->with('warning', 'Select Required Spear parts/battery ');
                }
            }
            // if (count($visit_plan->serviceProducts) < 1) {
            //     return redirect()->back()->with('warning', 'Select Product For Service Product');
            // }
        }

        if ($visit_plan->service_type == 'sales') {
            if (count($visit_plan->sales_items) < 1) {
                return redirect()->back()->with('warning', 'Select Product For Sale');
            }
        }

        if ($visit_plan->service_type == 'collection') {
            $request->validate([
                'collection_amount' => 'required',
                'payment_method' => 'required',
            ]);
            if ($request->payment_method == 'cash') {
                $request->validate([
                    'payment_collection_date' => 'required|date'
                ]);
            }
            if ($request->payment_method == 'cheque') {
                $request->validate([
                    'payment_maturity_date' => 'required|date',
                    'check_number' => 'required'
                ]);
            }


            if ($visit_plan->payment_maturity_date) {
                if ($visit_plan->payment_collection_amount < $request->collection_amount) {
                    return back()->with('warning', "Collection Amount must be {$visit_plan->payment_collection_amount}. Because, This Collection came from Payment Maturity Date");

                    //Return Back Not Working
                }
            }
        }

        $visit->purpose_of_visit = $request->purpose_of_visit;
        $visit->sale_amount = $paid_amount ?? 0.00;
        $visit->remarks = $request->remarks;
        $visit->editedBy_id = Auth::id();
        $visit->save();

        if ($visit_plan->service_type == 'service') {
            $visit->call_description = implode(',', $request->call_description);
            $visit->ventilation_system = $request->ventilation_system ? 1 : 0;
            $visit->air_condition = $request->air_condition ? 1 : 0;
            $visit->room_temperature = $request->room_temperature;
            $visit->ac_temperature = $request->ac_temperature;
            $visit->ac_run_time = $request->ac_run_time;
            $visit->other = $request->other;
            $visit->imput_vat = $request->imput_vat;
            $visit->charging_v = $request->charging_v;
            $visit->charging_amp = $request->charging_amp;
            $visit->imput_freq = $request->imput_freq;
            $visit->earthen_voltage = $request->earthen_voltage;
            $visit->output_vac = $request->output_vac;
            $visit->output_freq = $request->output_freq;
            $visit->earthen_voltage_le = $request->earthen_voltage_le;
            $visit->earthen_voltage_ne = $request->earthen_voltage_ne;
            $visit->battery_brand = $request->battery_brand;
            $visit->battery_amp = $request->battery_amp;
            $visit->battery_qty = $request->battery_qty;
            $visit->battery_time = $request->battery_time;
            $visit->physical_condition = $request->physical_condition;
            $visit->other_one = $request->other_one;
            $visit->other_two = $request->other_two;
            $visit->mobile_number = $request->mobile_number;

            $date_time = $request->date . " " . $request->time . ":00";
            $visit->date_time = $date_time;

            $visit->save();

            // if ($request->motherboard_checked || $request->motherboard_cleaned || $request->ac_dc_cable_checked || $request->ac_dc_cable_cleaned || $request->breaker_checked || $request->breaker_cleaned || $request->socket_checked || $request->socket_cleaned || $request->battery_ernubak_checked || $request->battery_ernubak_cleaned) {


            $service_maintaince = $visit->service_maintainces;
            $service_maintaince->visit_plan_id = $visit_plan->id;
            $service_maintaince->visit_id = $visit->id;
            $service_maintaince->motherboard_checked = $request->motherboard_checked ? 1 : 0;
            $service_maintaince->motherboard_cleaned = $request->motherboard_cleaned ? 1 : 0;
            $service_maintaince->ac_dc_cable_checked = $request->ac_dc_able_checked ? 1 : 0;
            $service_maintaince->ac_dc_cable_cleaned = $request->ac_dc_able_cleaned ? 1 : 0;
            $service_maintaince->breaker_checked = $request->breaker_checked ? 1 : 0;
            $service_maintaince->breaker_cleaned = $request->breaker_cleaned ? 1 : 0;
            $service_maintaince->battery_ernubak_checked = $request->battery_ernubak_checked ? 1 : 0;
            $service_maintaince->battery_ernubak_cleaned = $request->battery_ernubak_cleaned ? 1 : 0;
            $service_maintaince->socket_checked = $request->socket_checked ? 1 : 0;
            $service_maintaince->socket_cleaned = $request->socket_cleaned ? 1 : 0;

            $service_maintaince->save();
            // }

            $visit->call_status = $request->call_status;
            if ($request->call_status == 'pending') {
                $visit->call_pending_at = now();
            } elseif ($request->call_status == 'reviewed') {
                $visit->call_reviewed_at = now();
            } elseif ($request->call_status == 'approved') {
                $visit->call_approved_at = now();
            } elseif ($request->call_status == 'completed') {
                $visit->call_completed_at = now();
            }
            $visit->remarks = $request->remarks;
            $visit->eng_signature = $request->eng_signature;
            $visit->eng_date = $request->eng_date;
            $visit->customer_signature = $request->customer_signature;
            $visit->customer_date = $request->customer_date;
            $visit->seal = $request->seal;
            $visit->save();
        }

        if (has_in_array($visit_plan->service_type, 'sales')) {
            $visit->sale_amount = $visit_plan->total_sales_price();
            $visit->save();
        }

        if (has_in_array($visit_plan->service_type, 'collection')) {
            if ($request->invoice) {
                $visit->next_paymnet_collection_date = $request->next_collection_date ?? null;
                $visit->invoice_id = $request->invoice;
            }
            $visit->payment_method = $request->payment_method;
            $visit->check_number = $request->check_number ?? null;
            $visit->collection_amount = $request->collection_amount;
            $visit->collection_details = $request->collection_details;
            $visit->payment_collection_date = $request->payment_collection_date ?? null;
            $visit->payment_maturity_date = $request->payment_maturity_date  ?? null;
            $visit->save();
        }

        if (has_in_array($visit_plan->service_type, 'collection')) {
            if ($request->invoice) {
                $visit->next_paymnet_collection_date = $request->next_collection_date ?? null;
                $visit->invoice_id = $request->invoice;
            }
            $visit->payment_method = $request->payment_method;
            $visit->check_number = $request->check_number ?? null;
            $visit->collection_amount = $request->collection_amount;
            $visit->collection_details = $request->collection_details;
            $visit->payment_collection_date = $request->payment_collection_date ?? null;
            $visit->payment_maturity_date = $request->payment_maturity_date  ?? null;
            $visit->save();
        }


        if (!$visit->date_time) {
            $visit->date_time = now();
            $visit->save();
        }

        $visit_plan->visited_at = now();
        $visit_plan->visit_start_at = now();
        $visit_plan->save();
        return redirect()->route('admin.visits', $visit_plan)->with('success', 'Visit Updated Successfully');
    }
}
