<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\CollectionHistory;
use App\Models\Customer;
use App\Models\CustomerOffer;
use App\Models\CustomerOfferItem;
use App\Models\Employee;
use App\Models\GlobalImage;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\OfficeLocation;
use App\Models\Product;
use App\Models\ProductBrand;
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
use Illuminate\Support\Facades\Mail;

class VisitController extends Controller
{
    use EmployeeTrait;

    public function customerVisits(Request $request, VisitPlan $visit_plan)
    {

        $employee = Auth::user()->employee;
        $visits = Visit::where('visit_plan_id', $visit_plan->id)->with('customer')->latest()->get();
        $customer = $visit_plan->customer;
        $offer = CustomerOffer::where('customer_id', $visit_plan->customer_id)
            ->where('visit_plan_id', $visit_plan->id)
            ->where('status', 'temp')
            ->first();
        if (!$offer) {
            $offer = new CustomerOffer;
            $offer->customer_id = $visit_plan->customer_id;
            $offer->visit_plan_id = $visit_plan->id;
            $offer->addedBy_id = Auth::id();
            $offer->status = 'temp';
            $offer->save();
        }
        $customer_offer_items = CustomerOfferItem::where('customer_offer_id', $offer->id)->get();
        $brands = ProductBrand::all();
        return view('employee.visitPlan.visit.visit', compact('visit_plan', 'visits', 'offer', 'customer', 'customer_offer_items', 'brands', 'employee'));
    }
    public function customerVisitPlansOfInvoice(Request $request, Invoice $invoice)
    {
        $visit_plans = VisitPlan::where('invoice_id', $invoice->id)->with('customer')->latest()->get();
        return view('employee.visitPlan.visit.visit_plan-of-incoice', compact('visit_plans', 'invoice'));
    }

    public function customerOfferFinalSave(Request $request, Customer $customer, CustomerOffer $offer)
    {
        $request->validate([
            'date' => 'required|date'
        ]);
        if (count($offer->items) < 1) {
            return redirect()->back()->with('warning', 'First Add Some Product');
        }
        $offer->ref = $request->ref;
        $offer->date = $request->date;
        $offer->to = $request->to;
        $offer->subject = $request->subject;
        $offer->body = $request->body;
        $offer->signature = $request->signature;
        $offer->terms_and_condition = $request->terms_and_condition;
        if ($request->status) {
            $offer->status = $request->status;
        } else {
            $offer->status = 'pending';
        }

        $offer->pending_at = now();
        $offer->addedBy_id = Auth::id();
        $offer->save();

        if ($request->status == 'approved') {
            $offer->status = 'approved';
            $offer->approved_at = now();
            $offer->editedBy_id = Auth::id();

            $offer->total_quantity = $offer->total_quantity();
            $offer->total_unit_price = $offer->total_unit_price();
            $offer->total_price = $offer->total_price();
        }

        $offer->save();
        if ($offer->email) {
            Mail::to($offer->email)->send(new \App\Mail\QuatationSend($offer));
        }

        return redirect()->back()->with('success', 'Offer Created Successfully. Please Wait for Approved');
    }

    public function customerVisitCreate(Request $request, VisitPlan $visit_plan)
    {

        $visit = Visit::where('visit_plan_id', $visit_plan->id)->first();
        $sibling_visit_id = null;

        if ($visit) {
            $sibling_visit_id = $visit->id;
        }

        $sales_items = SalesItems::where('visit_plan_id', $visit_plan->id)->get();


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

        if ($visit_plan->call && $visit_plan->call->inhouse_product) {
            foreach ($visit_plan->call->call_products as $item) {
                $product = Product::find($item->product_id);
                $service_product_item = ServiceProduct::where('visit_plan_id', $visit_plan->id)->where('product_id', $product->id)->first();
                if (!$service_product_item) {
                    $service_product_item = new ServiceProduct;
                    $service_product_item->visit_plan_id = $visit_plan->id;
                    $service_product_item->product_id = $product->id;
                    $service_product_item->quantity = $item->quantity;
                    $service_product_item->device_name = $product->name;
                    $service_product_item->brand = $product->brand ? $product->brand->name : '';
                    $service_product_item->model_no = $product->model;
                    $service_product_item->capacity = $product->capacity;
                    $service_product_item->addedBy_id = Auth::id();
                    $service_product_item->save();
                }

                // $service_requirment = ServiceRequirementsofbatteryAndSpare::where('visit_plan_id', $visit_plan->id)->where('product_id', $product->id)->first();
                // if ($service_requirment) {
                //     $service_requirment->visit_plan_id = $visit_plan->id;
                //     $service_requirment->problem = $request->problem;
                //     $service_requirment->product_id = $item->product_id;
                //     $service_requirment->product_type =  $product->product_type;
                //     $service_requirment->quantity = $item->quantity;
                //     $service_requirment->unit = $request->unit;
                //     $service_requirment->addedBy_id = Auth::id();
                //     if ($request->visit_id) {
                //         $service_requirment->visit_id = $request->visit_id;
                //     }
                //     $service_requirment->save();
                // }
            }
        }
        $service_products = ServiceProduct::where('visit_plan_id', $visit_plan->id)->where('visit_id', null)->get();

        return view('employee.visitPlan.visit.visitAdd', compact('visit_plan', 'sales_items', 'service_products', 'complain_no', 'attachments', 'product_categories', 'sibling_visit_id', 'invoice'));
    }
    public function customerVisitStore(Request $request, VisitPlan $visit_plan)
    {
        // dd(implode(',',$request->call_description));
        // dd($request->all());

        // if (count($visit_plan->visits) > 0) {
        //     return redirect()->back()->with('warning', 'This Visit Plan has Already Visit');
        // }

        $employee = Auth::user()->employee;

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
        }

        // if (has_in_array($visit_plan->service_type, 'sales')) {
        //     if (count($visit_plan->sales_items) < 1) {
        //         return redirect()->back()->with('warning', 'Select Product For Sale');
        //     }
        // }

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
        $visit->call_id = $visit_plan->call_id ?? null;
        $visit->sibling_visit_id = $request->sibling_visit_id ?? null;
        $visit->employee_id = $employee->id;
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


        return redirect()->route('employee.customerVisits', $visit_plan)->with('success', 'Visit Created Successfully');
    }
    public function customerVisitview(Request $request, VisitPlan $visit_plan, Visit $visit)
    {
        $visit = Visit::where('id', $visit->id)->with('customer', 'employee', 'files', 'serviceProducts', 'service_requirment_batt_spear_parts', 'sales_items', 'service_maintainces')->first();
        $invoice = null;
        if ($visit_plan->invoice) {
            $invoice = $visit_plan->invoice;
        }
        return view('employee.visitPlan.visit.viewVisit', [
            'visit' => $visit,
            'visit_plan' => $visit_plan,
            'invoice' => $invoice,
        ]);
    }
    public function customerVisitEdit(Request $request, VisitPlan $visit_plan, Visit $visit)
    {

        if ($this->is_my_employee($visit->employee_id) == false) {
            abort(403);
        }
        if ($visit->status != 'pending') {
            return redirect()->back()->with('warning', 'You are not Able to Edit This Visit');
        }
        $sales_items = SalesItems::where('visit_plan_id', $visit_plan->id)->get();
        $categories = ProductCategory::whereHas('products')->get();
        $invoice = $visit->invoice;
        return view('employee.visitPlan.visit.editVisit', compact('visit_plan', 'visit', 'sales_items', 'categories', 'invoice'));
    }

    public function categoryToServiceProduct(Request $request)
    {

        $category_id = $request->category_id;
        $visit_plan = VisitPlan::find($request->visit_plan);
        $visit = Visit::find($request->visit);
        $offer = CustomerOffer::find($request->offer);
        return response()->json([
            'success' => true,
            'html' => view('employee.visitPlan.visit.ajax.categoryToServiceProduct', compact('visit_plan', 'visit', 'category_id'))->render()
        ]);
    }
    public function categoryToSaleProduct(Request $request)
    {
        $category_id = $request->category_id;
        $visit_plan = VisitPlan::find($request->visit_plan);
        $visit = Visit::find($request->visit);
        $offer = CustomerOffer::find($request->offer);
        return response()->json([
            'success' => true,
            'html' => view('employee.visitPlan.visit.ajax.categoryToSalesProduct', compact('visit_plan', 'visit', 'category_id'))->render()
        ]);
    }

    public function customerVisitUpdate(Request $request, VisitPlan $visit_plan, Visit $visit)
    {


        if ($this->is_my_employee($visit->employee_id) == false) {
            abort(403, 'Permission Denaid');
        }

        $employee = Auth::user()->employee;

        //Validation Start
        if (has_in_array($visit_plan->service_type, 'service')) {
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

        if (has_in_array($visit_plan->service_type, 'sales')) {
            if (count($visit_plan->sales_items) < 1) {
                return redirect()->back()->with('warning', 'Select Product For Sale');
            }
        }

        if (has_in_array($visit_plan->service_type, 'collection')) {
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

        // if (has_in_array($visit_plan->service_type, 'collection')) {
        //     $request->validate([
        //         'collection_amount' => 'required',
        //     ]);

        // }
        //Validation End

        $visit->purpose_of_visit = $request->purpose_of_visit;
        $visit->sale_amount = $paid_amount ?? 0.00;
        $visit->remarks = $request->remarks;
        $visit->editedBy_id = Auth::id();
        $visit->save();

        if (has_in_array($visit_plan->service_type, 'service')) {
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

            if ($request->motherboard_checked || $request->motherboard_cleaned || $request->ac_dc_cable_checked || $request->ac_dc_cable_cleaned || $request->breaker_checked || $request->breaker_cleaned || $request->socket_checked || $request->socket_cleaned || $request->battery_ernubak_checked || $request->battery_ernubak_cleaned) {


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
            }

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

        // if (has_in_array($visit_plan->service_type, 'collection')) {
        //     $visit->collection_amount = $request->collection_amount;
        //     $visit->collection_details = $request->collection_details;
        //     $visit->payment_collection_date = $request->payment_collection_date;
        //     $visit->payment_maturity_date = $request->payment_maturity_date;
        //     $visit->save();

        // }

        if (!$visit->date_time) {
            $visit->date_time = now();
            $visit->save();
        }

        $visit_plan->visited_at = now();
        $visit_plan->visit_start_at = now();
        $visit_plan->save();
        return redirect()->route('employee.customerVisits', $visit_plan)->with('success', 'Visit Updated Successfully');
    }
    public function customerVisitStatusUpdate(Request $request, VisitPlan $visit_plan, Visit $visit)
    {

        // if ((!$visit->addedBy_id == Auth::id()) ||  ($this->is_my_employee($visit->employee_id) == false)) {
        //     abort(403);
        // }
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

            // $visit->status = 'completed';
            // $visit->call_status = 'done';
            // $visit->completed_by = Auth::id();
            // $visit->completed_at = now();
            // $visit->save();
            // if ($call = $visit_plan->call) {
            //     $call->done_at = now();
            //     $call->done_by = Auth::id();
            //     $call->save();
            // }
            // $visit_plan->status = 'completed';
            // $visit_plan->save();
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
    public function customerVisited(Request $request, VisitPlan $visitPlan)
    {
        if ($visitPlan->customer_office_location_id) {
            return redirect()->back()->with('warning', 'You Already Visited');
        } else {
            $user_location = UserLocation::where('user_id', Auth::id())->whereDate('created_at', Carbon::now())->latest()->first();

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
                $prev_customer_office_location = OfficeLocation::where('title', $visitPlan->customer_address)->first();
                if ($prev_customer_office_location) {
                    $customer_office_location = $prev_customer_office_location;
                } else {
                    $customer_office_location = new OfficeLocation;
                    $customer_office_location->title = $visitPlan->customer_address;
                    $customer_office_location->customer_company_id = $visitPlan->customer->company_id;
                    $customer_office_location->lat = $user_location->lat;
                    $customer_office_location->lng = $user_location->lng;
                    $customer_office_location->type = 'customer';
                    $customer_office_location->active = true;
                    $customer_office_location->addedBy_id = Auth::id();
                    $customer_office_location->save();
                }
            }

            $visitPlan->customer_office_location_id = $customer_office_location->id;
            $visitPlan->visit_start_at = now();
            $visitPlan->save();
            return redirect()->back()->with('success', 'Thanks For Visit');
        }
    }
    public function allOfMyTeamMemberVisits(Request $request)
    {

        $status = $request->status;
        $sibling_visit_id = $request->sibling ?? null;
        $type = $request->type;
        menuSubmenu('visits', "visit_" . $type);
        if ($request->sibling) {
            $visits  = Visit::where('id', $sibling_visit_id)->paginate(100);
        } else {
            if ($type == 'today') {
                $visits = Visit::whereIn('employee_id', $this->alEmployees())
                    ->whereDate('date_time', Carbon::now())
                    ->orderBy('date_time', 'DESC')
                    ->paginate(100);
            } elseif ($type == 'pending') {
                $visits = Visit::whereIn('employee_id', $this->alEmployees())
                    ->where('status', 'pending')
                    ->orderBy('date_time', 'DESC')
                    ->paginate(100);
            } elseif ($type == 'approved') {
                $visits = Visit::whereIn('employee_id', $this->alEmployees())
                    ->where(function ($q) use ($type) {
                        if ($type) {
                            $q->where('status', $type);
                        }
                    })
                    ->orderBy('date_time', 'DESC')
                    ->paginate(100);
            } elseif ($type == 'completed') {
                $visits = Visit::whereIn('employee_id', $this->alEmployees())
                    ->where('status', 'completed')
                    ->orderBy('date_time', 'DESC')
                    ->paginate(100);
            } else {
                $visits = Visit::whereIn('employee_id', $this->alEmployees())
                    ->where(function ($q) use ($status) {
                        if ($status) {
                            $q->where('status', $status);
                        }
                    })
                    ->paginate(100);
            }
        }

        return view('employee.visitPlan.visit.allOfMyTeamMemberVisits', compact('visits', 'type', 'status'));
    }
    public function tempSalesItemAjax(Request $request)
    {


        $product = Product::find($request->product_id);
        $visit_plan = VisitPlan::find($request->visit_plan);

        if ($request->visit_id) {
            $sales_item = SalesItems::where('product_id', $request->product_id)->where('visit_id', $request->visit_id)->first();
        } else {
            $sales_item = SalesItems::where('product_id', $request->product_id)->where('visit_plan_id', $request->visit_plan)->first();
        }

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
            if ($request->visit_id) {
                $sales_item->visit_id = $request->visit_id;
            }
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
                'sale_amount' => $visit_plan->total_sales_price(),
            ]);
        }

        $sales_item->editedBy_id = Auth::id();
        $sales_item->save();

        return response()->json([
            'success' => true,
        ]);
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
                'html' => view('employee.visitPlan.visit.part.sericeProductShow', ['product' => $service_product_item, 'visit_plan' => $visit_plan])->render()
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
}
