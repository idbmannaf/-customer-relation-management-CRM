<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Challan;
use App\Models\ChallanItem;
use App\Models\CollectionHistory;
use App\Models\CustomerOffer;
use App\Models\CustomerOfferItem;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\Requisition;
use App\Models\RequisitionProduct;
use App\Models\ServiceProduct;
use App\Models\StockHistory;
use App\Models\TempReceivedProduct;
use App\Models\UnusedRequisitionProduct;
use App\Models\Visit;
use App\Models\WarrantyClaim;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RequisitionController extends Controller
{
    public function requisitions(Request $request)
    {
        $type = $request->type;
        $status = $request->status;
        menuSubmenuSubsubMenu('requisitions', $type, $type . "-" . $status);
        $requisitions = Requisition::with('requisitionProducts')
            ->where('type', $type)
            ->where('status', $status)
            ->where(function ($q) use ($request) {
                if ($request->visit) {
                    $q->where('visit_id', $request->visit);
                }
            })
            ->latest()
            ->paginate();

        return view('admin.requisitions.index', compact('type', 'status', 'requisitions'));
        // return back();
    }

    public function requisition(Request $request, Visit $visit)
    {
        $type = $request->type;
        $requisitions = Requisition::with('requisitionProducts')
            ->where('type', $type)
            ->where('visit_id', $visit->id)
            ->where('status', "<>", 'temp')
            ->latest()
            ->get();

        return view('admin.requisitions.requisition', compact('type', 'visit', 'requisitions'));
    }
    public function addRequisition(Request $request, Visit $visit)
    {

        if ($visit->status != 'approved') {
            return redirect()->back()->with('warning', 'You are not able to create customer offer/quotation. Because, This visit/Investigation is not Approved yet');
        }
        $type = $request->type;
        $requisition = Requisition::where('visit_id', $visit->id)->where('type', $type)->where('status', 'temp')->first();
        if (!$requisition) {
            $requisition = new Requisition;
            $requisition->visit_plan_id = $visit->visit_plan_id;
            $requisition->visit_id = $visit->id;
            $requisition->employee_id = $visit->employee_id;
            $requisition->customer_id = $visit->customer_id;
            $requisition->sales_person = $visit->customer->employee_id;
            $requisition->party_name = $visit->customer->customer_name . "-" . $visit->visit_plan->customer_address;
            if ($visit->visit_plan) {
                $requisition->delivery_address = $visit->visit_plan->service_address;
            }

            $requisition->addedBy_id = Auth::id();
            $requisition->type = $type;
            $requisition->status = 'temp';
            $requisition->save();
        }
        $warranty_claim = '';
        if ($type == 'warranty_claim') {
            $warranty_claim = WarrantyClaim::where('visit_id', $visit->id)
                ->where('requisition_id', $requisition->id)
                ->where('status', 'temp')
                ->first();
            if (!$warranty_claim) {
                $warranty_claim = new WarrantyClaim;
                $warranty_claim->requisition_id = $requisition->id;
                $warranty_claim->visit_plan_id = $visit->visit_plan_id;
                $warranty_claim->visit_id = $visit->id;
                $warranty_claim->employee_id = $visit->employee_id;
                $warranty_claim->prepared_by = Auth::id();
                $warranty_claim->reported_eng_name = $visit->employee_id;
                $warranty_claim->customer_id = $visit->customer_id;
                $warranty_claim->customer_address = $visit->visit_plan->customer_address;
                $warranty_claim->complain_no = $visit->id;
                $warranty_claim->complain_date = $visit->date_time;
                $warranty_claim->addedBy_id = Auth::id();
                $warranty_claim->status = 'temp';
                $warranty_claim->save();
            }
        }
        // dd($requisition->customer->customer_name);
        return view('admin.requisitions.addRequisition', compact('type', 'visit', 'requisition', 'warranty_claim'));
    }
    public function updateRequisition(Request $request, Visit $visit)
    {

        $requisition = Requisition::find($request->requisition);
        if ($request->type != 'warranty_claim') {
            if ($request->status != 'approved') {
                $requisition->sales_order_no = $request->sales_order_no;
                $requisition->party_name = $request->party_name;
                $requisition->date = $request->date;


                if ($requisition->status == 'temp' || $requisition->status == 'confirmed') {
                    $request->validate([
                        'date' => 'required|date',
                        'sales_order_no' => 'required',

                    ]);
                }

                if (!$request->products || count($request->products) < 1) {
                    return redirect()->back()->with('error', "Please Select Product For {$request->type} Requisition");
                }

                if ($request->type == 'inhouse_product') {
                    $requisition->remarks = $request->remarks;
                } else {
                    $requisition->mobile_number = $request->mobile_number;
                    $requisition->delivery_address = $request->delivery_address;
                    $requisition->receiver_name = $request->receiver_name;
                    $requisition->mobile = $request->mobile;
                    $requisition->last_invoice_date = $request->last_invoice_date;
                    $requisition->last_payment = $request->last_payment;
                    $requisition->amount = $request->amount;
                    $requisition->present_dues_amount = $request->present_dues_amount;
                    $requisition->payment_mode = $request->payment_mode;
                    $requisition->payment_date = $request->payment_date;
                    $requisition->commission_amount = $request->commission_amount;
                    $requisition->any_special_note = $request->any_special_note;
                }




                $requisition->amount_in_word = $request->amount_in_word;
                $requisition->status = $request->status ?? 'pending';

                if ($requisition->status == 'reviewed') {
                    $requisition->reviewed_by = Auth::id();
                    $requisition->reviewed_at = now();
                }

                $requisition->save();
                if ($requisition->requisitionProducts()->count()) {
                    $requisition->requisitionProducts()->delete();
                }
                $price = $request->unit_price;
                $qty = $request->qty;

                $req_product_final_price = 0;
                foreach ($request->products as $key => $offer_item_id) {
                    $offer_item = CustomerOfferItem::find($offer_item_id);
                    $req_product = new RequisitionProduct;
                    $req_product->requisition_id = $requisition->id;
                    $req_product->employee_id = $requisition->employee_id;
                    $req_product->customer_id = $requisition->customer_id;
                    $req_product->customer_offer_item_id = $offer_item->id;
                    $req_product->visit_id = $requisition->visit_id;
                    $req_product->product_id = $offer_item->product_id;
                    $req_product->product_name = $offer_item->product_name ?? '';
                    $req_product->product_type = $offer_item->product_type;
                    $req_product->unit_price = $price[$offer_item_id] ?? 0;
                    $req_product->quantity = $qty[$offer_item_id] ?? 0;
                    $req_product->total_price = $req_product->quantity * $req_product->unit_price;
                    $req_product->addedBy_id = Auth::id();
                    $req_product->save();
                    $req_product_final_price +=  $req_product->total_price;
                }

                $requisition->req_product_total_price = $req_product_final_price;
                $requisition->save();
            } else {
                $requisition->status == 'approved';
                $requisition->approved_by = Auth::id();
                $requisition->approved_at = now();
                $requisition->save();
                return redirect()->back()->with('success', 'Requisition Approved Successfully');
            }
        } else {
            if ($request->status == 'temp' || $request->status == 'confirmed') {
                $request->validate([
                    'product' => 'required',
                    'date' => 'required|date',
                    'quantity' => 'required',
                    'warranty_provide_for' => 'required',
                    'solution.*' => 'required',
                    'solution' => 'required',
                ]);
            }
            $requisition = Requisition::find($request->requisition);
            $product = Product::find($request->product_id);
            $warranty_claim = WarrantyClaim::find($request->warranty_claim_id);
            $status = $request->status ?? 'pending';
            if ($status == 'temp' || $status == 'pending' || $status == 'approved') {
                $service_product = ServiceProduct::find($request->item_id ?? $warranty_claim->item_id);
                $requisition->party_name = $warranty_claim->customer_address;
                $requisition->date = $request->date;
                $requisition->save();

                $warranty_claim->item_id = $request->item_id;
                $warranty_claim->complain_no = $visit->id;
                $warranty_claim->product_id = $product->id;
                $warranty_claim->product_name = $product->name;
                $warranty_claim->quantity = $request->quantity;
                $warranty_claim->warranty_period = $request->warranty_period;
                $warranty_claim->old_product_serial_number = $service_product->serial_no;
                $warranty_claim->warranty_provide_for = $request->warranty_provide_for;
                $warranty_claim->comment = $request->comment;
                $warranty_claim->eng_mobile_number = $request->eng_mobile_number;
                $warranty_claim->prepared_by = Auth::id();
                $warranty_claim->sale_date = $request->sale_date;
                $warranty_claim->invoice = $request->invoice;
                $warranty_claim->before_charge_v = $request->before_charge_v;
                $warranty_claim->after_charge_v = $request->after_charge_v;
                $warranty_claim->testing_load_with = $request->testing_load_with;
                $warranty_claim->backup_time = $request->backup_time;
                $warranty_claim->for_ups_and_others = $request->for_ups_and_others;
                $warranty_claim->current_due = $request->current_due;
                $warranty_claim->last_payment = $request->last_payment;
                $warranty_claim->date = $request->date;
                $warranty_claim->remarks = $request->remarks;
                $warranty_claim->solution = implode(',', $request->solution);
            }

            $requisition->status = $status;
            $warranty_claim->status = $status;
            if ($warranty_claim->status == 'confirmed') {
                $warranty_claim->manager = Auth::id();
                $warranty_claim->confirmed_at = now();
            }
            if ($warranty_claim->status == 'reviewed') {
                $warranty_claim->oparation_manager = Auth::id();
                $warranty_claim->reviewed_at = now();
            }
            $warranty_claim->save();
            $requisition->save();
        }

        return redirect()->back()->with('success', "{$request->type} Requisition Updated Successfully");
    }
    public function editRequisition(Request $request, Visit $visit)
    {

        $requisition = Requisition::find($request->requisition);
        $type = $request->type;

        return view('admin.requisitions.editRequisition', compact('type', 'visit', 'requisition'));
    }
    public function requisitionDetails(Request $request)
    {
        $type = $request->type;
        $status = $request->status;
        $requisition = Requisition::with('requisitionProducts')->find($request->requisition);

        $visit = $requisition->visit;

        return view('admin.requisitions.details', compact('requisition', 'type', 'visit'));
    }
    public function requisitionUpdate(Request $request, Requisition $requisition)
    {
        $status = $request->status;
        if ($request->type == 'warranty_claim') {
            if ($request->status == 'temp' || $request->status == 'confirmed') {

                $request->validate([
                    'product' => 'required',
                    'date' => 'required|date',
                    'quantity' => 'required',
                    'warranty_provide_for' => 'required',
                ]);
            }

            $product = Product::find($request->product_id);
            // dd($product);
            $warranty_claim = WarrantyClaim::find($request->warranty_claim_id);
            $service_product = ServiceProduct::find($request->item_id ?? $warranty_claim->item_id);



            if ($status == 'Pending' || $status == 'Temp') {
                $requisition->party_name = $warranty_claim->customer_address;
                $requisition->date = $request->date;
                $requisition->save();
                $warranty_claim->item_id = $request->item_id;
                $warranty_claim->product_id = $product->id;
                $warranty_claim->product_name = $product->name;
                $warranty_claim->quantity = $request->quantity;
                $warranty_claim->warranty_period = $request->warranty_period;
                $warranty_claim->old_product_serial_number = $service_product->serial_no;
                $warranty_claim->warranty_provide_for = $request->warranty_provide_for;
                $warranty_claim->comment = $request->comment;
                $warranty_claim->eng_mobile_number = $request->eng_mobile_number;
                $warranty_claim->prepared_by = Auth::id();
                $warranty_claim->sale_date = $request->sale_date;
                $warranty_claim->invoice = $request->invoice;
                $warranty_claim->before_charge_v = $request->before_charge_v;
                $warranty_claim->after_charge_v = $request->after_charge_v;
                $warranty_claim->testing_load_with = $request->testing_load_with;
                $warranty_claim->backup_time = $request->backup_time;
                $warranty_claim->for_ups_and_others = $request->for_ups_and_others;
                $warranty_claim->current_due = $request->current_due;
                $warranty_claim->last_payment = $request->last_payment;
                $warranty_claim->date = $request->date;
                $warranty_claim->remarks = $request->remarks;
                $warranty_claim->solution = implode(',', $request->solution);
            }


            $status = strtolower($status) ?? 'pending';
            $warranty_claim->status = $status;
            $requisition->status = $status;
            if ($warranty_claim->status == 'confirmed') {
                $warranty_claim->manager = Auth::id();
                $warranty_claim->confirmed_at = now();
            }
            if ($warranty_claim->status == 'reviewed') {
                $warranty_claim->oparation_manager = Auth::id();
                $warranty_claim->reviewed_at = now();
            }
            if ($warranty_claim->status == 'approved') {
                $warranty_claim->account_department = Auth::id();
                $warranty_claim->approved_at = now();
            }
            $warranty_claim->save();
            $requisition->save();
        } else {
            if ($status == 'Approved' || $status == 'Reviewed And Approved') {

                if ($status == 'Reviewed And Approved') {
                    $requisition->reviewed_at = now();
                    $requisition->reviewed_by =  Auth::id();
                }
                $requisition->status = 'approved';
                $requisition->approved_by = Auth::id();
                $requisition->approved_at = now();
                $requisition->editedBy_id = Auth::id();
                $requisition->save();

                $invo = Challan::max('invoice_no');
                $chall = Challan::max('challan_no');
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

                $challan = new Challan;
                $challan->invoice_no = $invoice_number;
                $challan->challan_no = $challan_number;
                $challan->employee_id = Auth::user()->employee ? Auth::user()->employee->id : '';
                $challan->customer_id = $requisition->customer_id;
                $challan->visit_plan_id = $requisition->visit_plan_id;
                $challan->visit_id = $requisition->visit_id;
                $challan->requisition_id = $requisition->id;
                $challan->offer_id = $requisition->visit ? ($requisition->visit->offer_quotation ? $requisition->visit->offer_quotation->id : '') : '';
                $challan->buyer_name = $requisition->customer ? $requisition->customer->customer_name : '';
                $challan->buyer_phone = $requisition->mobile;
                $challan->buyer_address = $requisition->customer ? $requisition->customer->client_address : '';
                $challan->delivery_address = $requisition->delivery_address;
                $challan->date_time = now();
                $challan->date = date('Y-m-d');
                $challan->time = date('h:i:s');
                $challan->attention = null;
                $challan->s_order_no = $requisition->sales_order_no;
                $challan->payment_dead_line = "Payment within 30 Days";
                $challan->buyer_ref_no = $requisition->visit ? ($requisition->visit->offer_quotation ? $requisition->visit->offer_quotation->ref : '') : '';
                $challan->payment_terms = 'cash';
                $challan->save();
                $req_products = $requisition->requisitionProducts;
                $total_price = 0;
                $total_qty = 0;
                foreach ($req_products as $rq_p) {
                    $challan_item = new ChallanItem;
                    $challan_item->challan_id = $challan->id;
                    $challan_item->employee_id = $challan->employee_id;
                    $challan_item->customer_id = $challan->customer_id;
                    $challan_item->requisition_id = $challan->requisition_id;
                    $challan_item->requisition_product_id = $rq_p->id;
                    $challan_item->product_id = $rq_p->product_id;
                    $challan_item->product_name = $rq_p->product ? $rq_p->product->name : '';
                    $challan_item->quantity = $rq_p->quantity;
                    $challan_item->unit_price = $rq_p->unit_price;
                    $challan_item->total_price = $challan_item->quantity * $challan_item->unit_price;
                    $challan_item->save();

                    $total_price += $challan_item->total_price;
                    $total_qty +=  $challan_item->quantity;
                }
                $challan->total_quantity = $total_qty;
                $challan->total_price = $total_price;
                $challan->save();
            }

            if ($status == 'Reviewed') {
                $requisition->sales_order_no = $request->sales_order_no;
                $requisition->party_name = $request->party_name;
                $requisition->date = $request->date;
                if ($requisition->status == 'temp' || $requisition->status == 'confirmed') {
                    $request->validate([
                        'date' => 'required|date',
                        'sales_order_no' => 'required',

                    ]);
                }

                if (!$request->products || count($request->products) < 1) {
                    return redirect()->back()->with('error', "Please Select Product For {$request->type} Requisition");
                }


                if ($request->type == 'inhouse_product') {
                    $requisition->remarks = $request->remarks;
                } else {
                    $requisition->mobile_number = $request->mobile_number;
                    $requisition->delivery_address = $request->delivery_address;
                    $requisition->receiver_name = $request->receiver_name;
                    $requisition->mobile = $request->mobile;
                    $requisition->last_invoice_date = $request->last_invoice_date;
                    $requisition->last_payment = $request->last_payment;
                    $requisition->amount = $request->amount;
                    $requisition->present_dues_amount = $request->present_dues_amount;
                    $requisition->payment_mode = $request->payment_mode;
                    $requisition->payment_date = $request->payment_date;
                    $requisition->commission_amount = $request->commission_amount;
                    $requisition->any_special_note = $request->any_special_note;
                }

                $requisition->amount_in_word = $request->amount_in_word;
                $requisition->status = 'reviewed';
                $requisition->reviewed_by = Auth::id();
                $requisition->reviewed_at = now();

                $requisition->save();
                if ($requisition->requisitionProducts()->count()) {
                    $requisition->requisitionProducts()->delete();
                }
                $price = $request->unit_price;
                $qty = $request->qty;
                $req_product_final_price = 0;
                foreach ($request->products as $key => $product_id) {
                    $product = Product::find($product_id);
                    $req_product = new RequisitionProduct;
                    $req_product->requisition_id = $requisition->id;
                    $req_product->employee_id = $requisition->employee_id;
                    $req_product->customer_id = $requisition->customer_id;
                    $req_product->visit_id = $requisition->visit_id;
                    $req_product->product_id = $product_id;
                    $req_product->product_type = $product->product_type;
                    $req_product->product_name = $product->name ?? '';
                    $req_product->unit_price = $price[$product_id] ?? 0;
                    $req_product->quantity = $qty[$product_id] ?? 0;
                    $req_product->total_price = $req_product->quantity * $req_product->unit_price;
                    $req_product->addedBy_id = Auth::id();
                    $req_product->save();
                    $req_product_final_price +=  $req_product->total_price;
                }
                $requisition->req_product_total_price = $req_product_final_price;
                $requisition->service_charge = $request->service_charge;
                $requisition->req_product_final_price = $req_product_final_price +  $request->service_charge;
                $requisition->save();
            }




            // if ($requisition->status == 'pending' || $requisition->status == 'reviewed') {

            //     if ($requisition->req_product_final_price > 0 && $requisition->requisitionProducts) {
            //         if ($requisition->visit->offer_quotation) {
            //             if ($requisition->visit->offer_quotation->items) {
            //                 $requisition->visit->offer_quotation->items()->delete();
            //             }
            //             $offer = $requisition->visit->offer_quotation;
            //             foreach ($requisition->requisitionProducts as $req_product) {
            //                 $product = Product::find($req_product->product_id);
            //                 $customer_offer_item = new CustomerOfferItem;
            //                 $customer_offer_item->customer_id = $requisition->customer_id;
            //                 $customer_offer_item->customer_offer_id = $offer->id;
            //                 $customer_offer_item->product_id = $req_product->product_id;
            //                 $customer_offer_item->product_name = $req_product->product_name;
            //                 $customer_offer_item->product_brand = $product->brand ? $product->brand->name : '';
            //                 $customer_offer_item->product_type = $product->type;
            //                 $customer_offer_item->product_origin = $product->origin;
            //                 $customer_offer_item->product_made_in = $product->made_in;
            //                 $customer_offer_item->product_warranty = $product->warranty;
            //                 $customer_offer_item->product_capacity = $product->capacity;
            //                 $customer_offer_item->quantity = $req_product->quantity;
            //                 $customer_offer_item->unit_price = $req_product->unit_price;
            //                 $customer_offer_item->total_price = $req_product->total_price;
            //                 $customer_offer_item->save();
            //             }

            //             $offer->total_quantity = $offer->total_quantity();
            //             $offer->total_unit_price = $offer->total_unit_price();
            //             $offer->total_price = $offer->total_price();
            //             $offer->save();
            //         } else {

            //             $this->customerOfferQuotation($requisition);
            //         }
            //     }
            // }
        }

        return redirect()->back()->with('success', "{$request->type} Requisition Updated Successfully");
    }

    public function customerOfferQuotation($requisition)
    {
        $products = $requisition->requisitionProducts()->pluck('product_name');
        $products_name = Product::find($products)->pluck('name');
        $offer = new CustomerOffer;
        $offer->customer_id = $requisition->customer_id;
        $offer->employee_id = $requisition->employee_id;
        $offer->visit_plan_id = $requisition->visit->visit_plan_id;
        $offer->visit_id = $requisition->visit_id;
        $offer->requisition_id = $requisition->id;
        $offer->addedBy_id = Auth::id();
        $offer->ref = "req-" . $requisition->id;
        $offer->date = $requisition->date;
        $to = '<p>University of Liberal Arts Bangladesh (ULAB)</p><p>
        House 56, Road 4/A Dhanmondi,</p><p>
        Dhaka-1209, Bangladesh.
        </p>';

        $products = $requisition->requisitionProducts->pluck('product_name')->toArray();
        $subject =  "Price offer for supplying of " . rtrim(implode(", ", $products));
        $body = '<p class="MsoNormal" style="text-align: justify;"><span style="font-size: 11pt; font-family: Calibri, sans-serif;">Dear Sir,<o:p></o:p></span></p><p class="MsoNormal" style="text-align: justify;"><span style="font-size: 11pt; font-family: Calibri, sans-serif;">With pleasure we would like to submit herewith our most competitive price offer of the above-mentioned&nbsp;</span><span style="font-family: Calibri, sans-serif; font-size: 11pt;">product for your kind acceptance. For your kind information, Orient Computers is the sole Distributor in Bangladesh of&nbsp;</span><span style="font-weight: bolder; font-family: Calibri, sans-serif; font-size: 11pt;">LONG</span><span style="font-family: Calibri, sans-serif; font-size: 11pt;">&nbsp;brand SLAMF Battery from Taiwan.</span></p><p class="MsoNormal" style="text-align: justify;"><span style="font-size: 11pt; font-family: Calibri, sans-serif;">We are also the sole Distributor of&nbsp;<span style="font-weight: bolder;">Apollo</span>&nbsp;brand Line Interactive, Online UPS, IPS, Automatic Voltage Stabilizer and Auto Rescue Device from Taiwan since&nbsp;<span style="font-weight: bolder;">1998</span>. Besides we are the&nbsp;<span style="font-weight: bolder;">Authorized Service Provider (ASP)&nbsp;</span>of Online and Offline Smart UPS in Bangladesh<span style="font-weight: bolder;">&nbsp;</span>of&nbsp;<span style="font-weight: bolder;">APC by SCHNEIDER ELECTRIC</span>. As well as we are the sole Distributor of&nbsp;<span style="font-weight: bolder;">ViewSonic</span>&nbsp;brand Multimedia Projector,&nbsp;<span style="font-weight: bolder;">SAMSUNG &amp; MICRODIGITAL</span>&nbsp;CCTV System from Korea,&nbsp;<span style="font-weight: bolder;">CAMPRO</span>&nbsp;brand CCTV Solutions from Taiwan,&nbsp;<span style="font-weight: bolder;">VIDEOTEC</span>&nbsp;Explosion Proof CCTV from Italy and&nbsp;<span style="font-weight: bolder;">HUNDURE</span>&nbsp;Access Control &amp; Time Attendance System from Taiwan and&nbsp;<span style="font-weight: bolder;">Dr. Board</span>&nbsp;Brand Interactive Smart Board from Taiwan.&nbsp;</span></p><p class="MsoNormal" style="text-align: justify;"><span style="font-size: 11pt; font-family: Calibri, sans-serif;">We have an organized service center with necessary equipment’s and skilled technical personnel who are capable to serve any problem anywhere in Bangladesh. We are serving number of organizations against Annual Maintenance Contract (AMC). Your positive response in this regard will be highly appreciated.</span></p><p class="MsoNormal" style="text-align: justify;"><span style="font-size: 11pt; font-family: Calibri, sans-serif;"><br></span><span style="font-family: Calibri, sans-serif; font-size: 11pt;">Thanking you in anticipation.</span></p><p class="MsoNormal" style="text-align: justify;"><span style="font-family: Calibri, sans-serif; font-size: 11pt;"><br></span><span style="font-family: Calibri, sans-serif; font-size: 11pt;">Thanking you,</span></p>';
        $signature = '<div style="line-height:5px; font-family: Calibri, sans-serif; font-size: 11pt;">
        <p>' . $requisition->employee->name . '</p><p>' . $requisition->employee->designation->title . '</p>

        </div>';

        $terms_and_condition = '<div style="font-size: 11pt; font-family: Calibri, sans-serif;">1. Payment&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : 100% Cash/cheque within 15 days in favor of Orient Computers.</div><div style="font-size: 11pt; font-family: Calibri, sans-serif;">2. Delivery&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: Within 15 days from date of work order.</div><div style="font-size: 11pt; font-family: Calibri, sans-serif;">3. Offer Validity&nbsp; &nbsp; &nbsp; &nbsp; :<span style="font-family: Arial;"> 15 da</span>ys</div><div style="font-size: 11pt; font-family: Calibri, sans-serif;">4. VAT &amp; TAX&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: Included</div><div style="font-size: 11pt; font-family: Calibri, sans-serif;">5. Site Preparation&nbsp; &nbsp;: Any civil work for site preparation is excluding of this proposal.</div><div style="font-size: 11pt; font-family: Calibri, sans-serif;">6. Accessories&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: AC Cables, Breakers, Earthing, Grounding are out of this proposal.</div><div style="font-size: 11pt; font-family: Calibri, sans-serif;">7. Warranty Void&nbsp; &nbsp; &nbsp; : Over Charged/Discharged, Burnt, Terminal Soldering, Physical Damage/Lost/Theft etc.</div>';
        $offer->to = $to;
        $offer->subject = $subject;
        $offer->body =  $body;
        $offer->signature = $signature;
        $offer->terms_and_condition = $terms_and_condition;
        $offer->status = 'pending';
        $offer->pending_at = now();
        $offer->addedBy_id = Auth::id();
        $offer->save();

        foreach ($requisition->requisitionProducts as $req_product) {
            $product = Product::find($req_product->product_id);
            $customer_offer_item = new CustomerOfferItem;
            $customer_offer_item->customer_id = $requisition->customer_id;
            $customer_offer_item->customer_offer_id = $offer->id;
            $customer_offer_item->product_id = $req_product->product_id;
            $customer_offer_item->product_name = $req_product->product_name;
            $customer_offer_item->product_brand = $product->brand->name;
            $customer_offer_item->product_type = $product->type;
            $customer_offer_item->product_origin = $product->origin;
            $customer_offer_item->product_made_in = $product->made_in;
            $customer_offer_item->product_warranty = $product->warranty;
            $customer_offer_item->product_capacity = $product->capacity;
            $customer_offer_item->quantity = $req_product->quantity;
            $customer_offer_item->unit_price = $req_product->unit_price;
            $customer_offer_item->total_price = $req_product->total_price;
            $customer_offer_item->save();
        }

        $offer->total_quantity = $offer->total_quantity();
        $offer->total_unit_price = $offer->total_unit_price();
        $offer->total_price = $offer->total_price();
        $offer->save();
    }
    public function oldStockProduct(Request $request)
    {
        $status = $request->status;
        menuSubmenu('oldStocks', 'OS_' . $status);
        if ($status == 'repair') {
            $products = UnusedRequisitionProduct::where('status', $status)
                ->latest()
                ->paginate(100);
        }
        if ($status == 'recharge') {
            $products = UnusedRequisitionProduct::where('status', $status)
                ->latest()
                ->paginate(100);
        }
        if ($status == 'bad') {
            $products = UnusedRequisitionProduct::where('total_bad','>', 0)
                ->latest()
                ->paginate(100);
        }

        if ($status == 'reuse') {
            $products = UnusedRequisitionProduct::where('total_reuse','>', 0)
                ->latest()
                ->paginate(100);
        }
        return view('admin.requisitions.old_stock.old_stock', compact('products', 'status'));
    }
    public function chalanAndInvoice(Request $request)
    {
        $type = $request->type;
        menuSubmenu('challanInvoice', "challanInvoice_" . $type);
        if ($type == 'challan') {
            $datas = Challan::latest()->paginate();
        }
        if ($type == 'invoice') {
            $datas = Invoice::latest()->paginate();
        }
        return view('admin.challan_invoice.index', compact('datas', 'type'));
    }
    public function chalanProductReceived(Request $request, Challan $challan)
    {
        if ($challan->product_received) {
            return redirect()->back()->with('warning', 'Product Already Reveived');
        }
        $challan->product_received = true;
        $challan->product_received_by = Auth::id();
        $challan->product_received_at = now();
        $challan->save();

        // create Invoice
        $visit = $challan->visit;
        $visit_plan = $challan->visit->visit_plan;
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
        return redirect()->back()->with('success', 'Product Reveived Successfully');
    }
    public function chalanAndInvoiceDetails(Request $request)
    {
        $type = $request->type;

        if ($type == 'invoice') {
            $data = Invoice::with('items')->find($request->id);
        }
        if ($type == 'challan') {
            $data = Challan::with('items')->find($request->id);
        }
        return view('admin.challan_invoice.details', compact('data', 'type'));
    }

    public function readyToReceiveProduct(Request $request)
    {
        $type = $request->type;
        menuSubmenu('readyToReceiveProducts', "RtR_" . $type);
        $requisitions = Requisition::where('type', $type)->where('status', 'approved')->where('send_to_receive_by', '!=', null)->latest()->paginate(100);
        return view('admin.recevied_products.index', compact('requisitions', 'type'));
    }
    public function readyToReceiveProductDetails(Request $request)
    {
        $type = $request->type;
        $requisition = Requisition::with('requisitionProducts')->find($request->requisition);
        $requ_products = RequisitionProduct::where('requisition_id', $requisition->id)->paginate(100);
        return view('admin.recevied_products.details', compact('type', 'requisition', 'requ_products'));
    }
    public function readyToReceiveProductDetailsUpdate(Request $request)
    {
        $requisition = Requisition::with('requisitionProducts')->find($request->requisition);
        // dd($requisition);

        $status = $request->status;
        if ($status == 'done') {
            $requisition->done_by = Auth::id();
            $requisition->done_at = now();
            $requisition->save();
        }
        if ($status == 'received') {
            $requisition->received_by = Auth::id();
            $requisition->received_at = now();
            $requisition->save();
        }
        return redirect()->back()->with('success', "Status {$status}");
    }

    public function unUsedProduct(Request $request)
    {
        $type = $request->type;
        menuSubmenu('unUsedProducts', "UU_" . $type);
        $requisitions = Requisition::where('type', $type)->where('status', 'approved')->pluck('id');
        $unusedProducts = RequisitionProduct::where(function ($q) {
            $q->where('used', false);
            $q->orWhere('return_old_product', true);
        })->orderBy('send')->orderBy('id', 'DESC')->paginate(100);
        return view('admin.unused_products.index', compact('type', 'unusedProducts'));
    }

    public function unUsedProductSendToStoreHead(Request $request, RequisitionProduct $item)
    {
        if ($item->send) {
            return back()->with('warning', 'This Product Already Sent');
        }
        $unusedProduct = new UnusedRequisitionProduct;
        $unusedProduct->requisition_id = $item->requisition_id;
        $unusedProduct->customer_id = $item->customer_id;
        $unusedProduct->return_old_product = $item->return_old_product ? 1 : 0;
        $unusedProduct->employee_id = $item->employee_id;
        $unusedProduct->customer_id = $item->customer_id;
        $unusedProduct->visit_id = $item->visit_id;
        $unusedProduct->visit_plan_id = $item->visit->visit_plan_id;
        $unusedProduct->product_id = $item->product_id;
        $unusedProduct->requisition_product_item_id = $item->id;
        $unusedProduct->product_name = $item->product_name;
        $unusedProduct->unit_price = $item->unit_price;
        $unusedProduct->quantity = $item->quantity;
        $unusedProduct->total_price = $item->total_price;
        $unusedProduct->type = $item->requisition->type;
        $unusedProduct->addedBy_id = Auth::id();
        $unusedProduct->save();
        $item->send = true;
        $item->send_by = Auth::id();
        $item->send_at = now();
        $item->save();
        return back()->with('success', 'This Product Send To The Store Head');
    }
    public function receiveUnusedProducts(Request $request)
    {
        $type = $request->type;
        menuSubmenu('receiveUnusedProducts', "RUP_" . $type);
        $receiveUnusedProducts = UnusedRequisitionProduct::where('type', $type)->orderBy('id', 'DESC')->where('visit_id', '!=', null)->paginate(100);
        return view('admin.received_unused_products.index', compact('type', 'receiveUnusedProducts'));
    }
    public function showReceivedProductInServiceTeamHead(Request $request)
    {
        $type = $request->type;
        menuSubmenu('receivedProductFromUnused', "RPOU_" . $type);
        $receivedProductFromUnused = UnusedRequisitionProduct::where('type', $type)->where('received', true)->orderBy('id', 'DESC')->paginate(100);
        return view('admin.damage_product_assign.damage_product_assign', compact('type', 'receivedProductFromUnused'));
    }

    public function sendReceiveUnusedProductToTeamMember(Request $request, UnusedRequisitionProduct $unused)
    {

        $status = $request->status;
        if ($status == 'received') {
            $unused->received = true;
            $unused->received_at = now();
            $unused->save();
            return redirect()->back()->with('success', 'Product Received Successfully');
        }
        if ($status == 'bad') {
            $unused->status = 'bad';
            $unused->bad_by = Auth::id();
            $unused->bad_at = now();
            $unused->save();
            return redirect()->back()->with('success', 'Product Badded Successfully');
        }

        $receiveUnusedProduct = UnusedRequisitionProduct::find($request->unused);
        return view('admin.received_unused_products.sendToTheTeamMember', compact('unused', 'status'));
    }
    public function sendReceiveUnusedProductToTeamMemberPost(Request $request)
    {
        $status = $request->status;
        $item = UnusedRequisitionProduct::find($request->unused);
        $request->validate([
            'assign_to' => 'required',
        ]);

        $item->status = $status;
        $item->assign_to = $request->assign_to;
        $item->save();

        return redirect()->back()->with('success', "Team Member Assigned to {$status}");
    }
    public function ProductOfRepairRecharge(Request $request)
    {
        $status = $request->status;

        menuSubmenu('repairRecharge', 'repairRecharge_', $status);
        $employee = Auth::user()->employee;
        $receiveUnusedProducts = UnusedRequisitionProduct::where('status', $status)->latest()->paginate(100);
        return view('admin.received_unused_products.repairOrRechargeProducts', compact('receiveUnusedProducts', 'status'));
    }
    public function receiveUnusedProductStatusUpdate(Request $request)
    {


        $status = $request->status;
        $item = UnusedRequisitionProduct::find($request->item);
        $quantity = $request->quantity;
        if ($quantity > $item->due()) {
            return redirect()->back('warning', "Current Quantity is {$item->due()} . Quantity must be less then equal to {$item->due()}");
        }
        if ($item->status == $status) {
            return back()->with('warning', "Status Already Update to {$status} Successfully");
        }
        if ($status == 'repair_status') {
            if ($request->update == 'reuse') {
                // $item->repair_status = 'use';
                $item->repair_use_at = now();
                $item->repair_use_by = Auth::id();
                $item->save();
                $item->increment('total_reuse', $quantity);
                $product = Product::find($item->product_id);
                $prev_stock = $product->stock;
                $product->stock = $product->stock + $quantity;
                $product->save();

                //Stock History In Reuse Start;
                $stock_history = new StockHistory;
                $stock_history->product_id = $product->id;
                $stock_history->product_name = $item->product_name;
                $stock_history->customer_id = $item->visit ? $item->visit->customer_id : null;
                $stock_history->previews_stock = $prev_stock;
                $stock_history->moved_stock = $quantity;
                $stock_history->current_stock = $product->stock;
                $stock_history->visit_plan_id = $item->visit_plan_id;
                $stock_history->visit_id = $item->visit_id;
                $stock_history->requisition_id = $item->requisition_id;
                $stock_history->purpose = 'Increment';
                $stock_history->requisition_product_item_id = $item->requisition_product_item_id;
                $stock_history->addedBy_id = Auth::id();
                $stock_history->save();
                if ($item->custom_entry) {
                    $stock_history->note = "This product {$item->product_name}($product->id) reuse from repair  and user { $stock_history->addedBy_id} Maintained This Product. This Product ({$product->id}) came  from  Custom Recieved Product -{$item->id}";
                } else {
                    $stock_history->note = "This product {$item->product_name}($product->id) reuse from repair  and user { $stock_history->addedBy_id} Maintained This Product. This Product ({$product->id}) came  from  Unused Product -{$item->id}";
                }

                $stock_history->save();
            } elseif ($request->update == 'bad') {
                // $item->repair_status = 'bad';
                $item->repair_bad_at = now();
                $item->repair_bad_by = Auth::id();

                $item->total_bad = $item->total_bad + $quantity;
                $item->bad_at = now();
                $item->bad_by = Auth::id();
                $item->save();
            }
        } elseif ($status == 'recharge_status') {
            // dd('recharge_status')
            if ($request->update == 'reuse') {
                // $item->recharge_status = 'use';
                $item->recharge_use_at = now();
                $item->recharge_use_by = Auth::id();
                $item->total_reuse = $item->total_reuse + $quantity;
                $item->save();

                $product = Product::find($item->product_id);
                $prev_stock = $product->stock;
                $product->stock = $product->stock + $quantity;
                $product->save();

                //Stock History Create Start;
                $stock_history = new StockHistory();
                $stock_history->product_id = $product->id;
                $stock_history->product_name = $item->product_name;
                $stock_history->customer_id = $item->visit ? $item->visit->customer_id : null;
                $stock_history->previews_stock = $prev_stock;
                $stock_history->moved_stock = $quantity;
                $stock_history->current_stock = $product->stock;
                $stock_history->visit_plan_id = $item->visit_plan_id;
                $stock_history->visit_id = $item->visit_id;
                $stock_history->purpose = 'Increment';
                $stock_history->requisition_id = $item->requisition_id;
                $stock_history->requisition_product_item_id = $item->requisition_product_item_id;
                $stock_history->addedBy_id = Auth::id();
                $stock_history->save();

                if ($item->custom_entry) {
                    $stock_history->note = "This product {$item->product_name}($product->id) reuse from Recharge  and user { $stock_history->addedBy_id} Maintained This Product. This Product ({$product->id}) cames from  Custom Recieved Product -{$item->id}";
                } else {
                    $stock_history->note = "This product {$item->product_name}($product->id) reuse from Recharge  and user { $stock_history->addedBy_id} Maintained This Product. This Product ({$product->id}) cames from  Unused Product -{$item->id}";
                }

                $stock_history->save();
            } elseif ($request->update == 'bad') {
                // $item->recharge_status = 'bad';
                $item->recharge_bad_at = now();
                $item->recharge_bad_by = Auth::id();

                $item->total_bad = $item->total_bad + $quantity;
                $item->bad_at = now();
                $item->bad_by = Auth::id();
                $item->save();
            }
        }

        $item->editedBy_id = Auth::id();
        $item->save();

        return back()->with('success', 'Status Updated Successfully');
    }
    public function receiveProductForStockManage(Request $request)
    {
        $type = $request->type;
        menuSubmenu('receiveProducts', "receiveProducts_" . $type);
        $receivedProducts = UnusedRequisitionProduct::where('type', $type)->where('custom_entry', true)->orderBy('id', 'DESC')->paginate(100);
        return view('admin.recevied_products.receiveProductForStockManage', compact('receivedProducts', 'type'));
    }

    public function addReceiveProductForStockManage(Request $request)
    {
        menuSubmenu('receiveProducts', "add_new_product");
        $temp_products = TempReceivedProduct::where('user_id', Auth::id())->get();
        return view('admin.recevied_products.addReceiveProductForStockManage', compact('temp_products'));
    }

    public function tempAddReceiveProductForStockManage(Request $request)
    {
        $product = Product::find($request->product);
        $temp_product = TempReceivedProduct::where('user_id', Auth::id())->where('product_id', $product->id)->first();
        if ($temp_product) {
            return response()->json([
                'success' => false,
                'message' => 'Product Already Added',
            ]);
        }

        $item = new TempReceivedProduct;
        $item->product_id = $product->id;
        $item->quantity = $request->quantity;
        $item->user_id = Auth::id();
        $item->save();
        return response()->json([
            'success' => true,
            'html' => view('employee.recevied_products.receiveProductAjax', compact('item'))->render()
        ]);
    }
    public function updateReceiveProductForStockManage(Request $request)
    {
        $type = $request->type;
        $item = TempReceivedProduct::find($request->item);
        if ($type == 'delete') {
            $item->delete();
            return response()->json([
                'success' => true,
                'type' => $type
            ]);
        }
        if ($type == 'quantity') {
            $item->quantity = $request->quantity;
            $item->save();
            return response()->json([
                'success' => true,
                'quantity' => $item->quantity,
                'type' => $type
            ]);
        }
        if ($type == 'details') {
            $item->details = $request->details;
            $item->save();
            return response()->json([
                'success' => true,
            ]);
        }
    }

    public function storeReceiveProductForStockManage(Request $request)
    {
        $items = TempReceivedProduct::where('user_id', Auth::id())->get();
        if (!$items) {
            return back()->with('error', 'First Addm Some Product or Spare Parts Then Submit');
        }
        $random_entry_no =  Str::random(25) . time();
        foreach ($items as $item) {
            $product = Product::find($item->product_id);
            $unused_product = new UnusedRequisitionProduct;
            $unused_product->product_id = $product->id;
            $unused_product->product_name = $product->name;
            $unused_product->unit_price = $product->unit_price;
            $unused_product->quantity = $item->quantity;
            $unused_product->total_price = $unused_product->quantity * $unused_product->unit_price;
            $unused_product->type = $product->product_type == 'spare_parts' ? 'spear_parts' : ($product->product_type == 'products' ? 'product' : '');
            $unused_product->details =  $item->details;
            $unused_product->entry_no = $random_entry_no;
            $unused_product->custom_entry = true;
            $unused_product->addedBy_id = Auth::id();
            $unused_product->save();
            $item->delete();
        }
        return back()->with('success', 'Product successfully Addedd');
    }

    public function readyForApproveReceiveProductForStockManage(Request $request)
    {
        $type = $request->type;
        menuSubmenu('changeStatusReceiveProductForStockManage', "changeStatusReceiveProductForStockManage_" . $type);
        $receivedProducts = UnusedRequisitionProduct::where('type', $type)->where('custom_entry', true)->orderBy('id', 'DESC')->paginate(100);
        return view('admin.recevied_products.changeStatusReceiveProductForStockManage', compact('receivedProducts', 'type'));
    }
    public function changeStatusReceiveProductForStockManage(Request $request, UnusedRequisitionProduct $item)
    {
        $item->received = true;
        $item->received_at = now();
        $item->save();
        return back()->with('success', 'Product successfully Approved');
    }
}
