<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerOffer;
use App\Models\CustomerOfferItem;
use App\Models\Offer;
use App\Models\Product;
use App\Models\ProductBrand;
use Auth;
use Illuminate\Http\Request;

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
        return view('employee.customers.offers.index', compact('customer', 'customer_offers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Customer $customer)
    {
        $employee = Auth::user()->employee;
        $offer = CustomerOffer::where('customer_id', $customer->id)
            ->where('employee_id', $employee->id)
            ->where('status', 'temp')
            ->first();
        if (!$offer) {
            $offer = new CustomerOffer;
            $offer->employee_id = $employee->id;
            $offer->customer_id = $customer->id;
            $offer->addedBy_id = Auth::id();
            $offer->status = 'temp';
            $offer->save();
        }
        $brands = ProductBrand::all();
        $customer_offer_items = CustomerOfferItem::where('customer_offer_id', $offer->id)->get();
        return view('employee.customers.offers.create', compact('customer', 'offer', 'customer_offer_items', 'brands'));
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
                'html' => view('employee.customers.offers.ajax.customerOfferItem', compact('customer', 'offer', 'customer_offer_item', 'serial_number', 'brands'))->render()
            ]);
        }
        // return view('employee.customers.offers.ajax.customerOfferItem',compact('customer','offer'));
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
        if ($type == 'quantity') {
            $customer_offer_item->quantity = $request->value;
            $customer_offer_item->total_price = $request->value * $customer_offer_item->unit_price;
            $customer_offer_item->save();
            return response()->json([
                'success' => true,
                'total_price' =>  $customer_offer_item->total_price,
            ]);
        }
        if ($type == 'unit_price') {
            $customer_offer_item->unit_price = $request->value;
            $customer_offer_item->total_price = $request->value * $customer_offer_item->quantity;
            $customer_offer_item->save();
            return response()->json([
                'success' => true,
                'total_price' =>  $customer_offer_item->total_price,
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
        $offer->status = 'pending';
        $offer->pending_at = now();
        $offer->addedBy_id = Auth::id();
        $offer->save();
        return redirect()->back()->with('success', 'Offer Created Successfully. Please Wait for Approved');
    }
    public function customerOfferEdit(CustomerOffer $offer)
    {
        $employee = Auth::user()->employee;
        $brands = ProductBrand::all();
        $customer = Customer::find($offer->customer_id);

        if ($offer->status != 'pending') {
            return redirect()->route('employee.customerOffer', $customer);
        }
        $customer_offer_items = CustomerOfferItem::where('customer_offer_id', $offer->id)->get();
        return view('employee.customers.offers.edit', compact('offer', 'customer', 'customer_offer_items', 'brands'));
    }
    public function customerOfferUpdate(Request $request, CustomerOffer $offer)
    {

        $customer = Customer::find($offer->customer_id);
        $request->validate([
            'ref' => 'required|unique:customer_offers,ref,' . $offer->id,
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
            $offer->total_unit_price = $offer->total_unit_price();
            $offer->total_price = $offer->total_price();
        }

        $offer->save();
        return redirect()->route('employee.customerOffer', $customer)->with('success', 'Offer Updated Successfully.');
    }
    public function customerOfferDetails(Request $request)
    {
       $offer = CustomerOffer::with('items')->find($request->offer);
       $customer = Customer::find($offer->customer_id);
       return view('employee.customers.offers.view', compact('offer','customer'));
    }
}
