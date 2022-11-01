<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerOffer;
use App\Models\CustomerOfferItem;
use App\Models\GlobalImage;
use App\Models\Invoice;
use App\Models\Offer;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use App\Models\ServiceProduct;
use App\Models\Visit;
use App\Models\VisitPlan;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CustomerOfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Customer $customer)
    {
        $customer_offers = CustomerOffer::with('customer')->where('status', '!=', 'temp')->orderBy('date', 'DESC')->paginate(30);
        return view('admin.customer.offers.index', compact('customer', 'customer_offers'));
    }
    public function customerOffers(Request $request)
    {

        $type = $request->type;
         if ($type == 'pending') {
            menuSubmenu('offers', 'customerOffers_pending');
            $offers = CustomerOffer::where('status','pending')
            ->where('status','!=','temp')
            ->with('items')
            ->latest()
            ->paginate(50);
        }elseif ($type =='approved') {
            menuSubmenu('offers', 'customerOffers_approved');
            $offers = CustomerOffer::where('status','approved')
            ->where('status','!=','temp')
            ->where('customer_approved',false)
            ->with('items')
            ->latest()
            ->paginate(50);
        }elseif ($type  == 'rejected') {
            menuSubmenu('offers', 'customerOffers_rejected');
            $offers = CustomerOffer::where('status','rejected')
            ->where('status','!=','temp')
            ->where('customer_approved',false)
            ->with('items')
            ->latest()
            ->paginate(50);
        }elseif ($type == 'customer_approved') {
            menuSubmenu('offers', 'customerOffers_customer_approved');
            $offers = CustomerOffer::where('status','approved')
            ->where('status','!=','temp')
            ->where('customer_approved',true)
            ->with('items')
            ->latest()
            ->paginate(50);
        }elseif ($type == 'all') {
            $offers = CustomerOffer::where('status','!=','temp')
            ->where('customer_approved',false)
            ->with('items')
            ->latest()
            ->paginate(50);
        }elseif ($type == 'customer_not_approved') {
            $offers = CustomerOffer::where('status','!=','temp')
            ->where('status','approved')
            ->where('customer_approved',false)
            ->with('items')
            ->latest()
            ->paginate(50);
        }
        return view('admin.customer.offers.offers', compact('offers','type'));
    }
    public function offerDetails(Customer $customer, CustomerOffer $offer)
    {


        return view('admin.customer.offers.offerDetails', compact('customer', 'offer'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,Customer $customer)
    {

        $visit = Visit::find($request->visit);

        $service_address = null;
        if ($visit && $visit->visit_plan) {
            if ($visit->visit_plan->call) {
                $service_address =$visit->visit_plan->call->service_address;
            }else{
                $service_address =$visit->visit_plan->service_address;
            }
        }
        if ($visit) {
            if ($visit->status != 'approved') {
                return redirect()->back()->with('warning', 'You are not able to create customer offer/quotation. Because, This visit/Investigation is not Approved yet');
            }
        }
        $subject = null;
        if (!$visit) {
            $offer = CustomerOffer::where('customer_id', $customer->id)
            ->where('addedBy_id',Auth::id())
                ->where('status', 'temp')
                ->where('visit_id', null)
                ->first();
            if (!$offer) {
                $offer = new CustomerOffer;
                $offer->customer_id = $customer->id;
                $offer->addedBy_id = Auth::id();
                $offer->status = 'temp';
                $offer->save();
            }
        } else {
            $offer = CustomerOffer::where('customer_id', $customer->id)
                ->where('addedBy_id',Auth::id())
                ->where('status', 'temp')
                ->where('visit_id', $visit->id)
                ->first();

            if (!$offer) {
                $offer = new CustomerOffer;
                $offer->customer_id = $customer->id;
                $offer->visit_id = $visit->id;
                $offer->to = $service_address;
                $offer->addedBy_id = Auth::id();
                $offer->status = 'temp';
                $offer->save();
            }
            $product_names = Product::whereIn('id', $visit->service_requirment_batt_spear_parts()->pluck('product_id'))->pluck('name')->toArray();
            $subject = implode(", ", $product_names);
        }

        $brands = ProductBrand::all();
        $customer_offer_items = CustomerOfferItem::where('customer_offer_id', $offer->id)->get();
        $attachments = GlobalImage::where('offer_id', $offer->id)->get();
        $categories = ProductCategory::whereHas('products')->get();

        return view('admin.customer.offers.create', compact('customer', 'attachments', 'offer', 'customer_offer_items', 'brands', 'categories', 'visit', 'subject'));


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }


    public function customerOfferItemAjax(Request $request)
    {
        $customer = Customer::find($request->customer);
        $offer = CustomerOffer::find($request->offer);
        $product = Product::find($request->product_id);
        $serial_number = $request->serial_number;
        $brands = ProductBrand::all();
        $customer_offer_item = CustomerOfferItem::where('customer_offer_id', $offer->id)->where('product_id', $product->id)->first();
        if (!$customer_offer_item) {
            $customer_offer_item = new CustomerOfferItem;
            $customer_offer_item->customer_id = $customer->id;
            $customer_offer_item->customer_offer_id = $offer->id;
            $customer_offer_item->product_id = $product->id;
            $customer_offer_item->product_name = $product->name;
            $customer_offer_item->product_brand = $product->brand->name;
            $customer_offer_item->product_type = $product->type;
            $customer_offer_item->product_origin = $product->origin;
            $customer_offer_item->product_made_in = $product->made_in;
            $customer_offer_item->product_warranty = $product->warranty;
            $customer_offer_item->product_capacity = $product->capacity;
            $customer_offer_item->quantity = 1;
            $customer_offer_item->unit_price = $product->unit_price;
            $customer_offer_item->total_price = $product->unit_price * $customer_offer_item->quantity;
            $customer_offer_item->save();

            return response()->json([
                'success' => true,
                'html' => view('admin.customer.offers.ajax.customerOfferItem', compact('customer', 'offer', 'customer_offer_item', 'serial_number', 'brands'))->render()
            ]);
        }
        // return view('admin.customer.offers.ajax.customerOfferItem',compact('customer','offer'));
    }
    public function customerOfferItemUpdate(Request $request)
    {

        $customer_offer = CustomerOffer::find($request->offer);
        $customer_offer_item = CustomerOfferItem::find($request->item);
        $product = Product::find($customer_offer_item->product_id);
        $type = $request->type;
        $value = $request->value;
        if ($type == 'name') {
            $customer_offer_item->product_name = $request->value;
        }
        if ($type == 'capacity') {

            $customer_offer_item->product_capacity = $request->value;
        }
        if ($type == 'brand') {
            $customer_offer_item->product_brand = ProductBrand::find($request->value)->name;
        }

        if ($type == 'warranty') {
            $customer_offer_item->product_warranty = $request->value;
        }
        if ($type == 'origin') {
            $customer_offer_item->product_origin = $request->value;
        }
        if ($type == 'made_in') {
            $customer_offer_item->product_made_in = $request->value;
        }

        if ($type == 'type') {
            $customer_offer_item->product_type = $request->value;
        }
        if ($type == 'service_charge') {
            $customer_offer->service_charge = $request->value;
            $customer_offer->subtotal_price = $customer_offer->total_price() + $customer_offer->service_charge;
            $customer_offer->save();
            return response()->json([
                'success' => true,
                'item_total_price' =>  $customer_offer_item->total_price,
                'total_price' =>  $customer_offer->total_price,
                'subtotal_price' =>  $customer_offer->subtotal_price,
            ]);
        }
        if ($type == 'quantity') {
            $customer_offer_item->quantity = $request->value;
            $customer_offer_item->total_price = $request->value * $customer_offer_item->unit_price;
            $customer_offer_item->save();

            $customer_offer->total_price = $customer_offer->total_price();
            $customer_offer->total_quantity = $customer_offer->total_quantity();
            $customer_offer->subtotal_price = $customer_offer->total_price() + $customer_offer->service_charge;
            $customer_offer->save();

            return response()->json([
                'success' => true,
                'item_total_price' =>  $customer_offer_item->total_price,
                'total_price' =>  $customer_offer->total_price,
                'subtotal_price' =>  $customer_offer->subtotal_price,
            ]);
        }
        if ($type == 'unit_price') {
            $customer_offer_item->unit_price = $request->value;
            $customer_offer_item->total_price = $request->value * $customer_offer_item->quantity;
            $customer_offer_item->save();

            $customer_offer->total_price = $customer_offer->total_price();
            $customer_offer->subtotal_price = $customer_offer->total_price() + $customer_offer->service_charge;
            $customer_offer->save();

            return response()->json([
                'success' => true,
                'item_total_price' =>  $customer_offer_item->total_price,
                'total_price' =>  $customer_offer->total_price,
                'subtotal_price' =>  $customer_offer->subtotal_price,
            ]);
        }
        $customer_offer_item->save();
        return response()->json([
            'success' => true,
        ]);
    }
    public function customerOfferItemDelete(Request $request)
    {
        $customer_offer_item = CustomerOfferItem::find($request->item);
        if ($customer_offer_item->delete()) {
            return response()->json([
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }
    public function customerOfferFinalSave(Request $request, CustomerOffer $offer)
    {
        $request->validate([
            'ref' => 'required|unique:customer_offers,ref',
            'date' => 'required|date',
            'email' => 'required|email'
        ]);
        $visit = Visit::find($request->visit);

        $total_price = 0;
        $total_quantity = 0;
        if (!$visit) {
            if (count($offer->items) < 1) {
                return redirect()->back()->with('warning', 'First Add Some Product');
            }
        } else {
            if ($visit->offer_id) {
                return redirect()->back()->with('warning', 'This Visit have a Offer/Quatation');
            }
            if ($visit->visit_plan->service_type == 'sales') {
                $total_quantity = $offer->total_quantity();
                $total_price = $offer->total_price();
                $service_charge = $offer->service_charge;
            } else {
                if (count($visit->service_requirment_batt_spear_parts) < 1) {
                    return redirect()->back()->with('warning', 'No spear_parts or product in Visit');
                }
                foreach ($visit->service_requirment_batt_spear_parts as $key => $item) {
                    $product = Product::find($item->product_id);
                    $offer_item = new CustomerOfferItem;
                    $offer_item->customer_offer_id = $offer->id;
                    $offer_item->customer_id = $offer->customer_id;
                    $offer_item->product_id = $product->id;
                    $offer_item->product_name = $product->name;
                    $offer_item->product_capacity = $product->capacity;
                    $product_brand = ProductBrand::where('id', $product->brand_id)->first();
                    $offer_item->product_brand = $product_brand ? $product_brand->id : '';
                    $offer_item->product_warranty = $product->warranty;
                    $offer_item->product_origin = $product->origin;
                    $offer_item->product_made_in = $product->made_in;
                    $offer_item->type = $product->type;
                    $offer_item->product_type = $product->product_type;
                    $offer_item->quantity = $item->quantity;
                    $offer_item->unit_price = $product->unit_price;
                    $offer_item->total_price =  $offer_item->quantity * $product->unit_price;
                    $offer_item->save();
                    $total_price += $offer_item->total_price;
                    $total_quantity += $offer_item->quantity;
                }
            }

            $visit->offer_id = $offer->id;
            $visit->save();
        }
        $service_charge = $request->service_charge ?? 0.00;
        $offer->total_quantity = $total_quantity;
        $offer->total_price = $total_price;
        $offer->service_charge = $service_charge;
        $offer->subtotal_price = $total_price + $service_charge;
        $offer->ref = $request->ref;
        $offer->email = $request->email;
        $offer->date = $request->date;
        $offer->to = $request->to;
        $offer->subject = $request->subject;
        $offer->body = $request->body;
        $offer->signature = $request->signature;
        $offer->terms_and_condition = $request->terms_and_condition;
        $offer->status = 'pending';
        $offer->pending_at = now();
        $offer->addedBy_id = Auth::id();
        $offer->save();
        if ($offer->email) {
            Mail::to($offer->email)->send(new \App\Mail\QuatationSend($offer));
        }
        if ($visit) {
            return redirect()->route('admin.visits', $visit->visit_plan_id)->with('success', 'successfully added');
        }
        return redirect()->back()->with('success', 'Offer Created Successfully. Please Wait for Approved');
    }
    public function customerOfferEdit(CustomerOffer $offer)
    {

        $brands = ProductBrand::all();
        $customer = Customer::find($offer->customer_id);

        $customer_offer_items = CustomerOfferItem::where('customer_offer_id', $offer->id)->get();
        $attachments = GlobalImage::where('offer_id',$offer->id)->get();
        if ($offer->customer_approved) {
            return view('admin.customer.offers.view', compact('offer', 'customer'));
        }
        return view('admin.customer.offers.edit', compact('offer', 'customer', 'customer_offer_items', 'brands','attachments'));
    }
    public function customerOfferUpdate(Request $request, CustomerOffer $offer)
    {

        $customer = Customer::find($offer->customer_id);
        $request->validate([
            // 'ref' => 'required|unique:customer_offers,ref,' . $offer->id,
            'date' => 'required|date',
            'status' => 'required',
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

        if ($request->status == 'pending') {
            $offer->status = 'pending';
            $offer->pending_at = now();
            $offer->editedBy_id = Auth::id();
            $offer->save();

            $offer->total_quantity = $offer->total_quantity();
            $offer->total_unit_price = $offer->total_unit_price();
            $offer->total_price = $offer->total_price();
            return redirect()->back()->with('success', 'Offer Created Successfully. Please Wait for Approved');
        }


        if ($request->status == 'rejected') {
            $offer->status = 'rejected';
            $offer->rejected_at = now();
            $offer->editedBy_id = Auth::id();
        }
        if ($request->status == 'approved') {
            $offer->status = 'approved';
            $offer->approved_at = now();
            $offer->editedBy_id = Auth::id();

            $offer->total_quantity = $offer->total_quantity();
            $offer->service_charge = $offer->service_charge;
            $offer->total_price = $offer->total_price();
            $offer->subtotal_price = $offer->total_price() + $offer->service_charge;

        }if ($request->status == 'update') {  ///After Approved Offer then Edit By Admin


            $offer->total_quantity = $offer->total_quantity();
            $offer->service_charge = $offer->service_charge;
            $offer->total_price = $offer->total_price();
            $offer->subtotal_price = $offer->total_price() + $offer->service_charge;

            $invoice = Invoice::where('offer_id',$offer->id)->first();
            if ($invoice) {
               $invoice->service_charge = $offer->service_charge;
               $invoice->net_amount = $offer->total_price;
               $invoice->total_amount = $offer->subtotal_price;
               $invoice->save();
            }
        }

        $offer->save();
        if ($offer->email) {
            Mail::to($offer->email)->send(new \App\Mail\QuatationSend($offer));
        }
        return redirect()->back()->with('success', 'Offer Updated Successfully.');
    }
    public function customerOfferDetails(Request $request)
    {

        $offer = CustomerOffer::with('items')->find($request->offer);
        $customer = Customer::find($offer->customer_id);
        return view('admin.customer.offers.view', compact('offer', 'customer'));
    }

    public function categoryToServiceProduct(Request $request)
    {

        $category_id = $request->category_id;
        $visit_plan = VisitPlan::find($request->visit_plan);
        $visit = Visit::find($request->visit);
        $offer = CustomerOffer::find($request->offer);
        return response()->json([
            'success' => true,
            'html' => view('admin.visitPlan.visit.ajax.categoryToServiceProduct', compact('visit_plan', 'visit', 'category_id'))->render()
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
            'html' => view('admin.visitPlan.visit.ajax.categoryToSalesProduct', compact('visit_plan', 'visit', 'category_id'))->render()
        ]);
    }
    public function customerOfferStatusUpdate(Request $request, CustomerOffer $offer)
    {

        $customer = Customer::find($offer->customer_id);
        $service_charge = $request->service_charge ?? 0.00;
        $offer->service_charge = $service_charge;
        $offer->subtotal_price = $offer->total_price() + $service_charge;
        if ($request->status == 'approved') {
            $offer->status = 'approved';
            $offer->approved_at = now();
            $offer->editedBy_id = Auth::id();
        }
        if ($request->status == 'customer_approved') {
            $offer->customer_approved =true;
            $offer->customer_approved_at = now();
            $offer->customer_approved_by = Auth::id();
        }

        $offer->save();
        return redirect()->route('admin.customerOfferDetails', $offer)->with('success', 'Offer Updated Successfully.');
    }

}
