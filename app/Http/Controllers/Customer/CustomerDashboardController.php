<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Call;
use App\Models\CallToSendProductRequest;
use App\Models\Challan;
use App\Models\CollectionHistory;
use App\Models\Company;
use App\Models\Customer;
use App\Models\CustomerCompany;
use App\Models\CustomerOffer;
use App\Models\District;
use App\Models\Division;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\OfficeLocation;
use App\Models\Team;
use App\Models\Upazila;
use App\Models\User;
use App\Models\UserLocation;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CustomerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $customer = $user->customer;
        // dd($customer->employee->teamHead);
        return view('customer.dashboard', compact('user', 'customer'));
    }
    public function myProfile(Request $request)
    {
        $user = Auth::user();
        $customer = $user->customer;
        return view('customer.view', compact('user', 'customer'));
    }
    public function editMyProfile(Request $request)
    {
        $user = Auth::user();
        $customer = $user->customer;
        $companies = CustomerCompany::where('active', true)->get();
        $divisions = DB::table('divisions')->select(['id', 'name'])->orderBy('name')->get();
        $districts = DB::table('districts')->select(['id', 'name', 'division_id'])->orderBy('name')->get();
        $thanas = DB::table('upazilas')->select(['id', 'name', 'district_id', 'division_id'])->orderBy('name')->get();
        $div = Division::where('name', 'like', '%' . $customer->division . '%')->first();
        $dis = District::where('name', 'like', '%' . $customer->district . '%')->first();

        $selectedDistricts = District::where('division_id', $div->id)->get();
        $selectedThanas = Upazila::where('district_id', $dis->id)->get();

        return view('customer.edit', compact('customer', 'companies', 'divisions', 'districts', 'thanas', 'selectedDistricts', 'selectedThanas'));
    }

    public function update(Request $request, Customer $customer)
    {
        $user = $customer->user;
        if ($request->type == 'password') {
            $request->validate([
                'old_password' => 'required',
                'password' => 'required|confirmed|min:5',
            ]);
            if (!password_verify($user->password, $request->password)) {
                return redirect()->back()->with('warning', 'Old Password Dose\'nt Match');
            } else {
                $user->password = Hash::make($request->password);
                $user->temp_password = $request->password;
                $user->save();
                return redirect()->back()->with('success', 'Password Updated Successfulloy');
            }
        } else {
            $request->validate([
                'email' => 'required|email|unique:users,email,' . $user->id,
                'company_name' => 'nullable',
                'customer_code' => 'nullable',
                'customer_name' => 'required',
                'client_address' => 'nullable',
                'division' => 'nullable',
                'district' => 'nullable',
                'thana' => 'nullable',
                'phone' => 'required',
                'area_id' => 'nullable',
                'contact_person_name' => 'nullable',
                'designation' => 'nullable',
                'mobile' => 'nullable',
            ]);
            if ($request->email != $customer->email) {
                $user->name = $request->customer_name;
                $user->username = $request->email;
                $user->track = true;
                $user->save();
            }

            $customer->user_id = $user->id;
            $customer->customer_code = $request->customer_code;
            $customer->customer_name = $request->customer_name;
            $customer->client_address = $request->client_address;
            $customer->phone = $request->phone;
            $customer->area_id = $request->area_id;
            $customer->customer_type = $request->customer_type;
            $customer->contact_person_name = $request->contact_person_name;
            $customer->designation = $request->designation;
            $customer->email = $request->email;
            $customer->mobile = $request->mobile;
            $customer->area = $request->area;
            if ($request->division) {
                $customer->division = Division::find($request->division)->name;
            }
            if ($request->district) {
                $customer->district = District::find($request->district)->name;
            }
            if ($request->thana) {
                $customer->thana = Upazila::find($request->thana)->name;
            }
            $customer->save();
            return back()->with('success', ' Your  Account  Successfully Updated.');
        }
    }



    public function viewUser(Request $request)
    {
        menuSubmenu('viewUser', 'viewUser');
        $user = User::find($request->user);
        if ($user->id != Auth::id()) {
            return back();
        }
        return view('users.view', compact('user'));
    }
    public function editUser(Request $request)
    {
        menuSubmenu('editUser', 'editUser');
        $user = User::find($request->user);
        if ($user->id != Auth::id()) {
            return back();
        }

        return view('users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);
        if ($request->type == 'user') {
            $request->validate([
                'name' => 'required',
                'email' => 'email|unique:users,email,' . $user->id,
                'image' => 'nullable |image|mimes:png,jpg'
            ]);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->track = $request->track_him ? 1 : 0;
            if ($file = $request->avater) {
                $f = 'users/' . $user->avater;
                if (Storage::disk('public')->exists($f)) {
                    Storage::disk('public')->delete($f);
                }

                $extension = $file->getClientOriginalExtension();
                $randomFileName = $user->id . Str::slug($user->name ?? 'No Name Found') . '_banner_' . date('Y_m_d_his') . '_' . rand(100, 999) . '.' . $extension;
                Storage::disk('public')->put('users/' . $randomFileName, File::get($file));
                $user->avater = $randomFileName;
            }
            $user->save();
        } else {
            $request->validate([
                'old_password' => 'required',
                'password' => 'required|confirmed|min:5'
            ]);
            if (password_verify($request->old_password, $user->password)) {
                $user->password = Hash::make($request->password);
                $user->temp_password = $request->password;
                $user->save();
            } else {
                return redirect()->back()->with('success', 'Old Password Dose not matched');
            }
        }

        return redirect()->back()->with('success', "{$request->type} Updated Successfully");
    }
    public function locationSet(Request $request)
    {
        $user = Auth::user();
        if ($user->track) {
            $location = UserLocation::where('user_id', Auth::id())->where('created_at', '>', now()->subMinutes(4))->latest()->first();

            if (!$location) {
                $location = new UserLocation;
                $location->lat = $request->lat;
                $location->lng = $request->lng;
                $location->ip = $request->ip();
                $location->user_id = $user->id;
                $location->addedBy_id = Auth::id();
                $location->save();

                if ($user->officeLocation) {
                    $radius = .2;
                    $company_lat = $user->officeLocation->lat ?? 23.7614067;
                    $company_lng = $user->officeLocation->lng ?? 90.4195412;
                    if ($company_lat and $company_lng) {
                        $haversine = "(6371 * acos(cos(radians(" . $company_lat . "))
                                        * cos(radians(`lat`))
                                        * cos(radians(`lng`)
                                        - radians(" . $company_lng . "))
                                        + sin(radians(" . $company_lat . "))
                                        * sin(radians(`lat`))))";
                    }

                    $user_lcation = UserLocation::select('id', 'lat', 'lng')
                        ->where('user_id', $user->id)
                        ->whereRaw("{$haversine} < ?", [$radius])
                        ->selectRaw("{$haversine} AS distance")
                        ->latest()
                        ->orderBy('distance')
                        ->first();

                    if ($user_lcation) {
                        $attendance = Attendance::where('user_id', $user->id)
                            // ->where('company_id', $team->company_id)
                            ->whereDate('date', Carbon::now()->format('Y-m-d'))
                            ->first();
                        if ($attendance) {
                            // $attendance->company_id = $team->company_id;
                            $attendance->lat = $user_lcation->lat;
                            $attendance->lng = $user_lcation->lng;
                            $attendance->ip = $request->ip();
                            $attendance->logged_out_at = now();
                            $attendance->save();
                        } else {
                            $attendance = new Attendance;
                            $attendance->user_id = $user->id;
                            $attendance->date = date('Y-m-d');
                            $attendance->company_id = $user->officeLocation->company_id;
                            $attendance->lat = $user_lcation->lat;
                            $attendance->lng = $user_lcation->lng;
                            $attendance->ip = $request->ip();
                            $attendance->logged_in_at = now();
                            $attendance->logged_out_at = now();
                            $attendance->save();
                        }

                        $this->Wo_RunInBackground(['lat' => $location->lat, 'lng' => $location->lng, 'success' => true,]);
                        $this->locationNameSet($request, $location);
                        return response()->json([
                            'success' => true,
                            'lat' => $location->lat,
                            'lng' => $location->lng,
                        ]);
                    }
                    return response()->json([
                        'success' => false,
                        'lat' => $location->lat,
                        'lng' => $location->lng,
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'lat' => $location->lat,
                        'lng' => $location->lng,
                    ]);
                }
            }
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }

    public function locationNameSet($request, $location)
    {
        $url = url("http://poi.18gps.net/poi?lat={$request->lat}&lon={$request->lng}");
        $client = new Client();
        $r = $client->request('GET', $url);
        $result = $r->getBody()->getContents();
        $location->location = $result ?? '';
        $location->save();
    }

    public function Wo_RunInBackground($data = array())
    {
        if (!empty(ob_get_status())) {
            ob_end_clean();
            header("Content-Encoding: none");
            header("Connection: close");
            ignore_user_abort();
            ob_start();
            if (!empty($data)) {
                header('Content-Type: application/json');
                echo json_encode($data);
            }
            $size = ob_get_length();
            header("Content-Length: $size");
            ob_end_flush();
            flush();
            session_write_close();
            if (is_callable('fastcgi_finish_request')) {
                fastcgi_finish_request();
            }
        }
    }
    public function calls(Request $request)
    {
        $user = Auth::user();
        $customer = $user->customer;
        menuSubmenu('colls', 'colls');
        $calls = Call::where('customer_id', $customer->id)->orderBy('date_time', 'DESC')->paginate(20);
        // dd($calls);
        return view('customer.calls.calls', compact('calls', 'customer'));
    }
    public function addCalls(Request $request)
    {

        $user = Auth::user();
        $customer = $user->customer;

        if ($request->isMethod('get')) {
            $office_location =  OfficeLocation::where('customer_company_id', $customer->company_id)->get();
            return view('customer.calls.addCalls', compact('office_location', 'customer'));
        }
        if ($request->isMethod('post')) {
            $request->validate([
                'date' => 'required|date',
                'time' => 'required',
                'purpose_of_visit' => 'required|string',
                'customer_office_location' => 'required',
            ]);
            $date_time = $request->date . " " . $request->time . ":00";
            $call = new Call;
            $call->date_time = $date_time;

            $call->type = $request->type;
            $call->customer_id = $request->customer;
            $call->employee_id = $customer->employee_id;
            $call->customer_address = $request->customer_office_location;
            $call->purpose_of_visit = $request->purpose_of_visit;
            $call->admin_note = $request->admin_note;
            $call->approved_at = $request->approved_at ? now() : null;
            $call->addedBy_id = Auth::id();
            $call->save();
            return redirect()->back()->with('success', 'Call Added Successfully');
        }
    }
    public function updateCalls(Request $request, Call $call)
    {
        $user = Auth::user();
        $customer = $user->customer;
        if ($request->isMethod('get')) {
            $office_location =  OfficeLocation::where('customer_company_id', $customer->company_id)->get();
            return view('customer.calls.editCalls', compact('call', 'customer', 'office_location'));
        }
        if ($request->isMethod('post')) {

            $request->validate([
                'date' => 'required|date',
                'time' => 'required',
                'purpose_of_visit' => 'required|string',
                'customer_office_location' => 'required',
            ]);
            $call->type = $request->type;
            $date_time = $request->date . " " . $request->time . ":00";
            $call->date_time = $date_time;
            $call->customer_location_id = $request->customer_office_location;
            $call->purpose_of_visit = $request->purpose_of_visit;
            $call->editedBy_id = Auth::id();
            $call->save();
            return redirect()->back()->with('success', 'Call Updated Successfully');
        }
    }
    public function offers(Request $request)
    {
        $user = Auth::user();
        $customer = $user->customer;
        $type = $request->type ?? 'all';
        menuSubmenu('offers', 'offers_' . $type);

        $offers = CustomerOffer::with('employee')->where('customer_id', $customer->id)->where('status', 'approved')->where(function ($q) use ($type) {
            if ($type == 'not_accepted') {
                $q->where('customer_approved', false);
            }
            if ($type == 'accepted') {
                $q->where('customer_approved', true);
            }
        })->latest()->paginate(10);

        return view('customer.offers', compact('offers', 'customer', 'type'));
    }
    public function offerDetails(CustomerOffer $offer)
    {
        $user = Auth::user();
        $customer = $user->customer;
        return view('customer.offerDetails', compact('customer', 'offer'));
    }
    public function offerUpdate(CustomerOffer $offer)
    {

        $offer->customer_approved = 1;
        $offer->customer_approved_by = Auth::id();
        $offer->customer_approved_at = now();
        $offer->save();
        return redirect()->back()->with('success', 'Offer Approved Successfully');
    }
    public function chalanAndInvoice(Request $request)
    {
        $user = Auth::user();
        $customer = $user->customer;

        $type = $request->type;
        menuSubmenu('challanInvoice', "challanInvoice_" . $type);
        if ($type == 'challan') {
            $datas = Challan::where('customer_id', $customer->id)->latest()->paginate();
        }
        if ($type == 'invoice') {
            $datas = Invoice::where('customer_id', $customer->id)->latest()->paginate();
        }
        return view('customer.challan_invoice.index', compact('datas', 'type'));
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

        return view('customer.challan_invoice.details', compact('data', 'type'));
    }
    public function transactionHistory(Request $request)
    {
        $user = Auth::user();
        $customer = $user->customer;
        $transaction_histories = CollectionHistory::where('customer_id', $customer->id)->latest()->paginate(100);
        return view('customer.challan_invoice.transaction_histories', compact('transaction_histories'));
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
        return redirect()->back()->with('success', 'Product Reveived Successfully');
    }
    public function sendTheProductInhouse(Request $request)
    {
        $user = Auth::user();
        $customer = $user->customer;
        $type = $request->type;
        menuSubmenu('sendTheProductInhouse','sendTheProductInhouse_',$type);
        if ($type == 'sent') {
            $products = CallToSendProductRequest::where('customer_id', $customer->id)->where('submited', true)->where('sent', true)->where('received', false)->latest()->paginate(100);
        } elseif ($type == 'unsent') {
            $products = CallToSendProductRequest::where('customer_id', $customer->id)->where('submited', true)->where('sent', false)->latest()->paginate(100);
        } elseif ($type == 'not_received') {
            $products = CallToSendProductRequest::where('customer_id', $customer->id)->where('submited', true)->where('sent', true)->where('received', false)->latest()->paginate(100);
        } elseif ($type == 'received') {
            $products = CallToSendProductRequest::where('customer_id', $customer->id)->where('submited', true)->where('received', true)->latest()->paginate(100);
        }elseif ($type == 'delivered') {
            $products = CallToSendProductRequest::where('customer_id', $customer->id)->where('submited', true)->where('delivered', true)->latest()->paginate(100);
        }

        return view('customer.send_product_in_houses', compact('products', 'type'));
    }
    public function sendTheProductItemInhouse(Request $request, CallToSendProductRequest $item)
    {
        $status = $request->status;
        if ($status =='received') {
            $item->customer_received = true;
            $item->customer_received_by = Auth::id();
            $item->customer_received_at = now();
            $item->save();
        }
        if ($status =='sent') {
            $item->sent = true;
            $item->sent_by = Auth::id();
            $item->sent_at = now();
            $item->save();
        }

        return back()->with('success', "Product {$status} Successfully");
    }
}
