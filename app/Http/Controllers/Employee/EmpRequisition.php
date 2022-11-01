<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Challan;
use App\Models\ChallanItem;
use App\Models\CollectionHistory;
use App\Models\Customer;
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
use App\Models\User;
use App\Models\Visit;
use App\Models\WarrantyClaim;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmpRequisition extends Controller
{
    // public $allowed = false;
    // public function __construct(Visit $visit)
    // {
    //     if ($visit->visit_plan->) {
    //         # code...
    //     }
    // }
    use EmployeeTrait;
    public function requisitionIndex(Request $request)
    {

        $type = $request->type;
        $status = $request->status;
        menuSubmenuSubsubMenu('requisitions', $type, $type . "-" . $status);
        $requisitions = Requisition::with('requisitionProducts')
            ->where('type', $type)
            ->where(function ($q) use ($status) {
                if ($status == 'done') {
                    $q->where('done_by', '!=', null);
                } else {
                    $q->where('done_by', null);
                    $q->where('status', $status);
                }
            })

            ->latest()
            ->paginate(100);
        return view('employee.requisition.index', compact('type', 'status', 'requisitions'));
    }
    public function requisitionDetails(Request $request)
    {

        $type = $request->type;
        $status = $request->status;
        $requisition = Requisition::with('requisitionProducts')->find($request->requisition);
        $visit = $requisition->visit;

        return view('employee.requisition.details', compact('requisition', 'type', 'visit'));
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

        return view('employee.requisition.requisitions', compact('type', 'visit', 'requisitions'));
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
        return view('employee.requisition.addRequisition', compact('type', 'visit', 'requisition', 'warranty_claim'));
    }
    public function editRequisition(Request $request, Visit $visit)
    {

        $requisition = Requisition::find($request->requisition);
        $type = $request->type;


        return view('employee.requisition.editRequisition', compact('type', 'visit', 'requisition'));
    }

    public function updateRequisition(Request $request, Visit $visit)
    {


        if ($request->type != 'warranty_claim') {
            $requisition = Requisition::find($request->requisition);
            $requisition->sales_order_no = $request->sales_order_no;
            $requisition->party_name = $request->party_name;
            $requisition->date = $request->date;

            // dd($request->status);
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

            // if ($requisition->status == 'pending' || $requisition->status == 'reviewed') {
            //     if ($requisition->req_product_final_price > 0 && $requisition->requisitionProducts) {
            //         $offer_quatation = $requisition->visit->offer_quotation;
            //         if ($offer_quatation) {
            //             if ($offer_quatation->items) {
            //                 $offer_quatation->items()->delete();
            //             }
            //             $offer =  $offer_quatation;
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

            //             if (!$requisition->visit->offer_id) {
            //                 $this->customerOfferQuotation($requisition);
            //             }
            //         }
            //     }
            // }
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

    public function inventoryMaintain(Request $request)
    {
        $employee = Auth::user()->employee;
        if (!$employee->company->inventory_maintain_permission) {
            return redirect()->back()->with('warning', "You are not able to maintain Inventory");
        }
        $type = $request->type;
        menuSubmenu('inventoryMaintain', $type);

        // $requisition_products = RequisitionProduct::whereHas('requisition',function($q) use ($type){
        //     $q->where('status', '!=', 'temp');
        //     $q->where('type', $type);
        //     $q->latest();

        // })->paginate(100);
        $requisitions = Requisition::where('status', 'approved')
            ->where('status', '!=', 'temp')
            ->where('type', $type)
            ->latest()
            ->paginate(100);

        return view('employee.inventory.index', compact('type', 'requisitions'));
    }
    public function inventoryMaintainDetails(Request $request)
    {
        $employee = Auth::user()->employee;
        $type = $request->type;
        $requisition = Requisition::find($request->requisition);
        if (!$employee->company->inventory_maintain_permission) {
            return redirect()->back()->with('warning', "You are not able to maintain Inventory");
        }
        return view('employee.inventory.details', compact('type', 'requisition'));
    }
    public function inventoryMaintainUpdate(Request $request)
    {

        $employee = Auth::user()->employee;
        $requisition = Requisition::find($request->requisition);
        $req_product = RequisitionProduct::find($request->item);
        $product = Product::find($req_product->product_id);

        if ($requisition->received_by || $requisition->done_by) {
            return redirect()->back()->with('warning', "Log Status Already Updated");
        }
        if (!$employee->company->inventory_maintain_permission) {
            return redirect()->back()->with('warning', "You are not able to maintain Inventory");
        }

        if (!$req_product || !$product) {
            return redirect()->back()->with('warning', "Product Not Found");
        }

        if ($product->stock < $req_product->quantity) {
            return redirect()->back()->with('warning', "This Product ({$req_product->product_name}) Doesn't Have Enough Stock. Current Stock:{$product->stock}");
        }

        $prev_stock = $product->stock;

        $product->stock = $product->stock - $req_product->quantity;
        $product->save();

        $req_product->stock_out = true;
        $req_product->ok_by = Auth::id();
        $req_product->ok_at = now();
        $req_product->save();

        //History Create Start;
        $stock_history = new StockHistory;
        $stock_history->product_id = $product->id;
        $stock_history->product_name = $req_product->product_name;
        $stock_history->customer_id = $requisition->visit ? $requisition->visit->customer_id : null;
        $stock_history->previews_stock = $prev_stock;
        $stock_history->moved_stock = $req_product->quantity;
        $stock_history->current_stock = $product->stock;
        $stock_history->visit_plan_id = $requisition->visit->visit_plan_id;
        $stock_history->visit_id = $requisition->visit_id;
        $stock_history->requisition_id = $requisition->id;
        $stock_history->purpose = "Decrease";
        $stock_history->requisition_product_item_id = $req_product->id;
        $stock_history->addedBy_id = Auth::id();
        $stock_history->save();

        $visit_plan_id = $requisition->visit->visit_plan_id;
        $stock_history->note = "User {$stock_history->addedBy_id} and Employee {$employee->id} Maintained This Product. This Product ({$product->id}) cames from Requisition ({$requisition->id}, Visit({$requisition->visit_id}) and  Visit Plan ({$visit_plan_id}) for Customer {$requisition->customer_id}";
        $stock_history->save();

        //History Create Start;
        return redirect()->back()->with('success', 'Quantity Decresed form Product Stock');
    }
    public function returnMaintainUpdate(Request $request)
    {
        $req_product = RequisitionProduct::find($request->item);
        $req_product->return_old_product = !$req_product->return_old_product;
        $req_product->save();
    }
    public function sendToReceived(Request $request)
    {
        // $request->validate([
        //     'courier_details'=>'required',
        //     'courier_slip'=>'required',
        // ]);
        $requisition = Requisition::find($request->requisition);
        $requisition->send_to_receive_by = Auth::id();
        $requisition->send_to_receive_at = now();
        $requisition->save();
        return redirect()->back()->with('success', 'Product sent for recevied');
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
            $customer_offer_item->product_brand = $product->brand ? $product->brand->name : '';
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

    public function inventoryStatusUpdateOfVisit(Request $request)
    {
        $status = $request->status;
        $visit = Visit::find($request->visit);
        if ($status == 'received') {
            $visit->received_by = Auth::id();
            $visit->received_at = now();
            $visit->save();
        }
        if ($status == 'done') {
            $visit->done_by = Auth::id();
            $visit->done_at = now();
            $visit->save();
        }
        return redirect()->back()->with('success', "Log {$status} Successfully");
    }
    public function readyToReceiveProduct(Request $request)
    {
        $type = $request->type;
        menuSubmenu('readyToReceiveProducts', "RtR_" . $type);
        $employees = $this->alEmployees();

        $requisitions = Requisition::whereIn('employee_id', $employees)->where('type', $type)->where('status', 'approved')->where('send_to_receive_by', '!=', null)->latest()->paginate(100);
        return view('employee.recevied_products.index', compact('requisitions', 'type'));
    }
    public function readyToReceiveProductDetails(Request $request)
    {
        $type = $request->type;
        $requisition = Requisition::with('requisitionProducts')->find($request->requisition);
        $requ_products = RequisitionProduct::where('requisition_id', $requisition->id)->paginate(100);
        return view('employee.recevied_products.details', compact('type', 'requisition', 'requ_products'));
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

    public function requisitionProductStatusUpdate(Request $request, RequisitionProduct $item)
    {
        $status = $request->status;
        if ($status == 'useunuse') {
            $item->used = !$item->used;
            $item->used_by = Auth::id();
            $item->used_at = now();
            $item->save();
        } else {
            $item->returned = !$item->returned;
            $item->returned_by = Auth::id();
            $item->returned_at = now();
            $item->save();
        }

        return true;
    }
    public function unUsedProduct(Request $request)
    {
        $type = $request->type;
        menuSubmenu('unUsedProducts', "UU_" . $type);
        $employees = $this->alEmployees();

        $requisitions = Requisition::whereIn('employee_id', $employees)->where('type', $type)->where('status', 'approved')->pluck('id');

        $unusedProducts = RequisitionProduct::whereIn('requisition_id', $requisitions)->where(function ($q) {
            $q->where('used', false);
            $q->orWhere('return_old_product', true);
        })->orderBy('send')->orderBy('id', 'DESC')->paginate(100);
        return view('employee.unused_products.index', compact('type', 'unusedProducts'));
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
        return view('employee.received_unused_products.index', compact('type', 'receiveUnusedProducts'));
    }
    public function showReceivedProductInServiceTeamHead(Request $request)
    {
        $type = $request->type;
        menuSubmenu('receivedProductFromUnused', "RPOU_" . $type);
        if (auth()->user()->employee->team_admin && auth()->user()->employee->company->store_damage_product_assign_permission) {

            $receivedProductFromUnused = UnusedRequisitionProduct::where('type', $type)->where('received', true)->orderBy('id', 'DESC')->paginate(100);
            return view('employee.damage_product_assign.damage_product_assign', compact('type', 'receivedProductFromUnused'));
        } else {
            abort(403);
        }
    }
    public function receiveUnusedProductStatusUpdate(Request $request)
    {


        $status = $request->status;
        $item = UnusedRequisitionProduct::find($request->item);
        $quantity = $request->quantity;
        if ($quantity > $item->due()) {
           return redirect()->back('warning',"Current Quantity is {$item->due()} . Quantity must be less then equal to {$item->due()}");
        }
        if ($item->status == $status) {
            return back()->with('warning', "Status Already Update to {$status} Successfully");
        }
        if ($status == 'repair_status') {
            if ($request->update == 'reuse') {
                // $item->repair_status = 'use';
                $item->repair_use_at = now();
                $item->repair_use_by = Auth::id();
                $item->total_reuse = $item->total_reuse + $quantity;
                $item->save();
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
                $stock_history = new StockHistory;
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

        $myEmployees = Employee::whereIn('id', $this->alEmployees())->orderBy('name')->get();
        $receiveUnusedProduct = UnusedRequisitionProduct::find($request->unused);
        return view('employee.received_unused_products.sendToTheTeamMember', compact('unused', 'status', 'myEmployees'));
    }
    public function ProductOfRepairRecharge(Request $request)
    {
        $status = $request->status;

        menuSubmenu('repairRecharge', 'repairRecharge_', $status);
        $employee = Auth::user()->employee;
        $receiveUnusedProducts = UnusedRequisitionProduct::where('status', $status)->where('assign_to', $employee->id)->latest()->paginate(100);
        return view('employee.received_unused_products.repairOrRechargeProducts', compact('receiveUnusedProducts', 'status'));
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
    public function visitToProductItemAjax(Request $request)
    {
        $item = ServiceProduct::find($request->item);
        if ($item) {
            return response()->json([
                'success' => true,
                'item_id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product ? $item->product->name : '',
                'old_product_serial_number' => $item->serial_no,
                'warranty_period' => $item->product ? $item->product->warranty : 'No warranty',
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }
    public function warrantyClaim(Request $request)
    {
        $status = $request->status;
        menuSubmenu('warrantyClaim', 'warrantyClaim_' . $status);
        $requisitions = Requisition::where('type', 'warranty_claim')->where('status', '!=', 'temp')->where('status', $status)->latest()->paginate(100);
        return view('employee.warranty_claim.store_head_warranty_claim', compact('requisitions'));
    }
    public function warrantyClaimDetails(Request $request, Requisition $requisition)
    {
        $warranty_claim = $requisition->warrantyClaim;
        return view('employee.warranty_claim.store_head_warranty_claim_details', compact('requisition', 'warranty_claim'));
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
        return view('employee.requisition.old_stock.old_stock', compact('products', 'status'));
    }
    public function chalanAndInvoice(Request $request)
    {

        $customer_ids = $this->my_customers()->pluck('id');
        $type = $request->type;
        menuSubmenu('challanInvoice', "challanInvoice_" . $type);
        if ($type == 'challan') {
            $datas = Challan::whereIn('customer_id', $customer_ids)->latest()->paginate();
        }
        if ($type == 'invoice') {
            $datas = Invoice::whereIn('customer_id', $customer_ids)->latest()->paginate();
        }
        return view('employee.challan_invoice.index', compact('datas', 'type'));
    }
    public function chalanAndInvoiceDetails(Request $request)
    {
        // dd(number_to_word(1154040));
        $type = $request->type;
        if ($type == 'invoice') {
            $data = Invoice::with('items')->find($request->id);
        }
        if ($type == 'challan') {
            $data = Challan::with('items')->find($request->id);
        }
        // dd($data);
        return view('employee.challan_invoice.details', compact('data', 'type'));
    }

    public function receiveProductForStockManage(Request $request)
    {
        $type = $request->type;
        menuSubmenu('receiveProducts', "receiveProducts_" . $type);
        $receivedProducts = UnusedRequisitionProduct::where('type', $type)->where('custom_entry', true)->orderBy('id', 'DESC')->paginate(100);
        return view('employee.recevied_products.receiveProductForStockManage', compact('receivedProducts', 'type'));
    }

    public function addReceiveProductForStockManage(Request $request)
    {
        menuSubmenu('receiveProducts', "add_new_product");
        $temp_products = TempReceivedProduct::where('user_id', Auth::id())->get();
        $customers =  Customer::whereIn('employee_id', $this->alEmployees())->get();
        return view('employee.recevied_products.addReceiveProductForStockManage', compact('temp_products','customers'));
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
            $item->quantity = $request->value;
            $item->save();
            return response()->json([
                'success' => true,
                'quantity' => $item->value,
                'type' => $type
            ]);
        }
        if ($type == 'details') {
            $item->details = $request->value;
            $item->save();
            return response()->json([
                'success' => true,
            ]);
        }
        if ($type == 'product_serial') {
            $item->product_serial = $request->value;
            $item->save();
            return response()->json([
                'success' => true,
            ]);
        }
        if ($type == 'invoice_id') {
            $item->invoice_id = $request->value;
            $item->save();
            return response()->json([
                'success' => true,
            ]);
        }
        if ($type == 'invoice_date') {
            $item->invoice_date = $request->value;
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
        $employee = auth()->user()->employee;
        $random_entry_no =  Str::random(25).time();
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
            $unused_product->employee_id = $employee->id;
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
        return view('employee.recevied_products.changeStatusReceiveProductForStockManage', compact('receivedProducts', 'type'));
    }
    public function changeStatusReceiveProductForStockManage(Request $request, UnusedRequisitionProduct $item)
    {
        $item->received = true;
        $item->received_at = now();
        $item->save();
        return back()->with('success', 'Product successfully Approved');
    }
}
