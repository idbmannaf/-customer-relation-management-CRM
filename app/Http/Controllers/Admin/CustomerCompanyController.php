<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\ImportCustomerOffice;
use App\Models\CustomerCompany;
use App\Models\District;
use App\Models\OfficeLocation;
use App\Models\Upazila;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class CustomerCompanyController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:customer-company', ['only' => ['index']]);
        $this->middleware('permission:customer-company-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:customer-company-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:customer-company-delete', ['only' => ['delete']]);
        $this->middleware('permission:customer-company-office', ['only' => ['customerCompanyOffice']]);
        $this->middleware('permission:customer-company-office-add', ['only' => ['customerCompanyOfficeCreate', 'customerCompanyOfficeStore']]);
        $this->middleware('permission:customer-company-office-edit', ['only' => ['customerCompanyOfficeEdit', 'customerCompanyOfficeUpdate']]);
        $this->middleware('permission:customer-company-office-delete', ['only' => ['customerCompanyOfficeDelete']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        menuSubmenu('customer_companies', 'all_customer_companies');
        $companies = CustomerCompany::orderBy('name')->paginate(50);
        return view('admin.customer.company.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        menuSubmenu('customer_companies', 'create_customer_companies');
        return view('admin.customer.company.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->type == 'bulk_upload') {
            $request->validate([
                'file' => 'required'
            ]);
            if ($request->file->getClientOriginalExtension() != 'xlsx') {
                return redirect()->back()->with('error', "Only xlsx File Allowed");
            }
            $upload = Excel::import(new ImportCustomerOffice, request()->file('file'),);
            if ($upload) {
                return redirect()->back()->with('success', "Customers Office Added Successfully");
            }
        }
        $request->validate([
            'name' => 'required',
            'active' => 'nullable',
        ]);
        $company = new CustomerCompany;
        $company->name = $request->name;
        $company->address = $request->address;
        $company->active = $request->active ? 1 : 0;
        $company->addedBy_id = Auth::id();
        $company->save();
        return redirect()->back()->with('success', ' Customer Company Added successfuly');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomerCompany  $customerCompany
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerCompany $customerCompany)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomerCompany  $customerCompany
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerCompany $customerCompany)
    {
        return view('admin.customer.company.edit', compact('customerCompany'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerCompany  $customerCompany
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerCompany $customerCompany)
    {
        $request->validate([
            'name' => 'required',
            'active' => 'nullable',
        ]);
        $customerCompany->name = $request->name;
        $customerCompany->address = $request->address;
        $customerCompany->active = $request->active ? 1 : 0;
        $customerCompany->editedBy_id = Auth::id();
        $customerCompany->save();
        return redirect()->back()->with('success', 'Customer Company Updated successfuly');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerCompany  $customerCompany
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerCompany $customerCompany)
    {
        $customerCompany->delete();
        return redirect()->back()->with('success', ' Company Deleted successfuly');
    }
    public function customerCompanyOffice(Request $request, CustomerCompany $customer_company)
    {
        return view('admin.customer.company.offices', compact('customer_company'));
    }
    public function customerCompanyOfficeCreate(Request $request, CustomerCompany $customer_company)
    {

        // menuSubmenu('officeLocation', 'createLocations');
        $divisions = DB::table('divisions')->select(['id', 'name'])->orderBy('name')->get();
        $districts = DB::table('districts')->select(['id', 'name', 'division_id'])->orderBy('name')->get();
        $thanas = DB::table('upazilas')->select(['id', 'name', 'district_id', 'division_id'])->orderBy('name')->get();

        return view('admin.customer.company.office.create', compact('divisions', 'districts', 'thanas', 'customer_company'));
    }
    public function customerCompanyOfficeStore(Request $request, CustomerCompany $customer_company)
    {
        $request->validate([
            'title' => 'required',
            'location' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'division' => 'nullable',
            'district' => 'nullable',
            'thana' => 'nullable',
            'featured_image' => 'nullable',
            'active' => 'nullable',
        ]);

        $office_location = new OfficeLocation;
        $office_location->title = $request->title;
        $office_location->google_location = $request->location;
        $office_location->lat = $request->lat;
        $office_location->lng = $request->lng;
        $office_location->division_id = $request->division;
        $office_location->district_id = $request->district;
        $office_location->thana_id = $request->thana;
        $office_location->office_start_time = $request->office_start_time;
        $office_location->office_end_time = $request->office_end_time;
        $office_location->customer_company_id = $customer_company->id;
        $office_location->active = $request->active ? 1 : 0;
        $office_location->addedBy_id = Auth::id();
        $office_location->type = 'customer';
        $office_location->save();

        if ($request->hasFile('featured_image')) {
            $file = $request->featured_image;
            $ext = "." . $file->getClientOriginalExtension();
            $imageName = Str::slug($office_location->title) . time() . $ext;
            Storage::disk('public')->put('officeLocations/' . $imageName, File::get($file));
            $office_location->featured_image = $imageName;
        }
        $office_location->save();
        return redirect()->back()->with('success', 'Customer Office Location Added Successfully');
    }


    public function customerCompanyOfficeEdit(Request $request, CustomerCompany $customer_company, OfficeLocation $office)
    {
        // menuSubmenu('officeLocation', 'createLocations');
        $divisions = DB::table('divisions')->select(['id', 'name'])->orderBy('name')->get();
        $districts = DB::table('districts')->select(['id', 'name', 'division_id'])->orderBy('name')->get();
        $thanas = DB::table('upazilas')->select(['id', 'name', 'district_id', 'division_id'])->orderBy('name')->get();
        $selected_districts = District::where('division_id', $office->division_id)->get();
        $selected_thanas = Upazila::where('district_id', $office->district_id)->get();
        return view('admin.customer.company.office.edit', compact('divisions', 'districts', 'thanas', 'customer_company', 'office', 'selected_districts', 'selected_thanas'));
    }

    public function customerCompanyOfficeUpdate(Request $request, CustomerCompany $customer_company, OfficeLocation $office)
    {
        $request->validate([
            'title' => 'required',
            'location' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'division' => 'nullable',
            'district' => 'nullable',
            'thana' => 'nullable',
            'featured_image' => 'nullable',
            'active' => 'nullable',
        ]);

        $office->title = $request->title;
        $office->google_location = $request->location;
        $office->lat = $request->lat;
        $office->lng = $request->lng;
        $office->division_id = $request->division;
        $office->district_id = $request->district;
        $office->thana_id = $request->thana;
        $office->office_start_time = $request->office_start_time;
        $office->office_end_time = $request->office_end_time;
        $office->customer_company_id = $customer_company->id;
        $office->active = $request->active ? 1 : 0;
        $office->editedBy_id = Auth::id();
        $office->type = 'customer';
        $office->save();

        if ($request->hasFile('featured_image')) {
            $old_file = 'officeLocations/' . $office->featured_image;
            if (Storage::disk('public')->exists($old_file)) {
                Storage::disk('public')->delete($old_file);
            }
            $file = $request->featured_image;
            $ext = "." . $file->getClientOriginalExtension();
            $imageName = Str::slug($office->title) . time() . $ext;
            Storage::disk('public')->put('officeLocations/' . $imageName, File::get($file));
            $office->featured_image = $imageName;
        }
        $office->save();
        return redirect()->back()->with('success', 'Customer Office Location Updated Successfully');
    }

    public function customerCompanyOfficeDelete(Request $request, CustomerCompany $customer_company, OfficeLocation $office)
    {
        $office->delete();
        return redirect()->back()->with('success', 'Customer Office Location Deleted Successfully');
    }


    public function customerCompanyCustomers(Request $request, CustomerCompany $customer_company)
    {
        return view('admin.customer.company.office.create', compact('customer_company'));
    }
}
