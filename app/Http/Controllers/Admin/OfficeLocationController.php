<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OffceLocationRequest;
use App\Imports\ImportCustomerOffice;
use App\Models\Company;
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

class OfficeLocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:office-location-list', ['only' => ['index']]);
        $this->middleware('permission:office-location-add', ['only' => ['store']]);
        $this->middleware('permission:office-location-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:office-location-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // $office_locations = OfficeLocation::where('type','company')->with('company')->latest()->paginate(30);
        // return view('admin.officeLocation.index',compact('office_locations'));

        $type = $request->type ?? '';
        if ($request->type == 'customer') {
            menuSubmenu('officeLocation', 'AllCustomerLocations');
            $office_locations = OfficeLocation::where('type', 'customer')->with('customer_company')->latest()->paginate(100);
            $q = '';
            if ($request->ajax()) {
                return view('admin.officeLocation.ajax.customerOfficelocationAjax', compact('office_locations', 'type', 'q'))->render();
            }
            return view('admin.officeLocation.customerCompanyLocation', compact('office_locations', 'type', 'q'));
        } else {
            menuSubmenu('officeLocation', 'AllLocations');
            $office_locations = OfficeLocation::where('type', 'company')->with('company')->latest()->paginate(30);
            return view('admin.officeLocation.index', compact('office_locations', 'type'));
        }
    }
    public function bulkUpload(Request $request)
    {
        $request->validate([
            'file' => 'required'
        ]);
        if ($request->file->getClientOriginalExtension() != 'xlsx') {
            return redirect()->back()->with('error', "Only xlsx File Allowed");
        }
        $upload = Excel::import(new ImportCustomerOffice, request()->file('file'),);
        if ($upload) {
            return redirect()->back()->with('success', "Customers Added Successfully");
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        menuSubmenu('officeLocation', 'createLocations');
        $divisions = DB::table('divisions')->select(['id', 'name'])->orderBy('name')->get();
        $districts = DB::table('districts')->select(['id', 'name', 'division_id'])->orderBy('name')->get();
        $thanas = DB::table('upazilas')->select(['id', 'name', 'district_id', 'division_id'])->orderBy('name')->get();
        $companies = Company::where('active', true)->get();
        return view('admin.officeLocation.create', compact('divisions', 'districts', 'thanas', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OffceLocationRequest $request)
    {
        $request->validate([
            'office_start_time'=>'required',
            'office_late_time'=>'required',
            'office_absent_time'=>'required',
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
        $office_location->office_late_time = $request->office_late_time;
        $office_location->office_absent_time = $request->office_absent_time;
        $office_location->company_id = $request->company;
        $office_location->active = $request->active ? 1 : 0;
        $office_location->addedBy_id = Auth::id();
        $office_location->type = 'company';
        $office_location->save();

        if ($request->hasFile('featured_image')) {
            $file = $request->featured_image;
            $ext = "." . $file->getClientOriginalExtension();
            $imageName = Str::slug($office_location->title) . time() . $ext;
            Storage::disk('public')->put('officeLocations/' . $imageName, File::get($file));
            $office_location->featured_image = $imageName;
        }
        $office_location->save();
        return redirect()->back()->with('success', 'Office Location Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $officeLocation = OfficeLocation::find($id);
        $divisions = DB::table('divisions')->select(['id', 'name'])->orderBy('name')->get();
        $districts = DB::table('districts')->select(['id', 'name', 'division_id'])->orderBy('name')->get();
        $thanas = DB::table('upazilas')->select(['id', 'name', 'district_id', 'division_id'])->orderBy('name')->get();
        $selected_districts = District::where('division_id', $officeLocation->division_id)->get();
        $selected_thanas = Upazila::where('district_id', $officeLocation->district_id)->get();
        $companies = Company::where('active', true)->get();
        return view('admin.officeLocation.edit', compact('officeLocation', 'divisions', 'districts', 'thanas', 'selected_districts', 'selected_thanas', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OffceLocationRequest $request, $id)
    {
        $request->validate([
            'office_start_time'=>'required',
            'office_late_time'=>'required',
            'office_absent_time'=>'required',
        ]);
        $office_location = OfficeLocation::find($id);
        $office_location->title = $request->title;
        $office_location->google_location = $request->location;
        $office_location->lat = $request->lat;
        $office_location->lng = $request->lng;
        $office_location->division_id = $request->division;
        $office_location->district_id = $request->district;
        $office_location->thana_id = $request->thana;
        $office_location->office_start_time = $request->office_start_time;
        $office_location->office_end_time = $request->office_end_time;
        $office_location->office_late_time = $request->office_late_time;
        $office_location->office_absent_time = $request->office_absent_time;
        $office_location->company_id = $request->company;
        $office_location->active = $request->active ? 1 : 0;
        $office_location->editedBy_id = Auth::id();
        $office_location->save();

        if ($request->hasFile('featured_image')) {
            $old_file = 'officeLocations/' . $office_location->featured_image;
            if (Storage::disk('public')->exists($old_file)) {
                Storage::disk('public')->delete($old_file);
            }
            $file = $request->featured_image;
            $ext = "." . $file->getClientOriginalExtension();
            $imageName = Str::slug($office_location->title) . time() . $ext;
            Storage::disk('public')->put('officeLocations/' . $imageName, File::get($file));
            $office_location->featured_image = $imageName;
        }
        $office_location->save();
        return redirect()->back()->with('success', 'Office Location Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $office_location = OfficeLocation::find($id);
        $office_location->delete();
        return redirect()->back()->with('success', 'Offoce Location Successfully Deleted');

        // if ($office_location->company) {
        //     return redirect()->back()->with('warning','you are not abole to delete this location');
        // }else{

        //     $office_location->delete();
        //     return redirect()->back()->with('success','Offoce Location Successfully Deleted');
        // }
    }

    public function customerOfficeCreate(Request $request)
    {
        // menuSubmenu('officeLocation', 'createLocations');
        $divisions = DB::table('divisions')->select(['id', 'name'])->orderBy('name')->get();
        $districts = DB::table('districts')->select(['id', 'name', 'division_id'])->orderBy('name')->get();
        $thanas = DB::table('upazilas')->select(['id', 'name', 'district_id', 'division_id'])->orderBy('name')->get();
        $customer_companies = CustomerCompany::where('active', true)->get();
        return view('admin.location.customer_office.create', compact('divisions', 'districts', 'thanas', 'customer_companies'));
    }

    public function customerCompanyOfficeSearch(Request $request)
    {
        $q = $request->q;
        $type = 'customer';
        $office_locations = OfficeLocation::where('type', 'customer')
        ->where('type','!=',null)
            ->where('title', 'like', '%' . $q . '%')
            ->orWhereHas('customer_company', function ($query) use ($q) {
                $query->where('name', 'like', '%' . $q . '%');
            })
            ->orWhere('booth_id', 'like', '%' . $q . '%')
            ->orWhere('amc_number', 'like', '%' . $q . '%')
            ->orWhere('booth_name', 'like', '%' . $q . '%')
            ->orWhere('item_code', 'like', '%' . $q . '%')
            ->latest()
            ->paginate(30);

            // dd($office_locations);
        return view('admin.officeLocation.ajax.customerOfficelocationAjax', compact('office_locations', 'type', 'q'))->render();
    }

    public function customerOfficeStore(Request $request)
    {

        $request->validate([
            'title' => 'required',
            'location' => 'nullable',
            'company' => 'required',
            'lat' => 'nullable',
            'lng' => 'nullable',
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
        $office_location->customer_company_id = $request->company;
        $office_location->address = $request->address;
        $office_location->serial_no = $request->serial_no;
        $office_location->start_date = $request->start_date;
        $office_location->end_date = $request->end_date;
        $office_location->amc_number = $request->amc_number;
        $office_location->billing_period = $request->billing_period;
        $office_location->item_code = $request->item_code;
        $office_location->location_type = $request->location_type;
        $office_location->booth_id = $request->booth_id;
        $office_location->booth_name = $request->booth_name;
        $office_location->mobile_number = $request->mobile_number;
        $office_location->ups_brand = $request->ups_brand;
        $office_location->model = $request->model;
        $office_location->capacity = $request->capacity;
        $office_location->battery_brand = $request->battery_brand;
        $office_location->battery_ah = $request->battery_ah;
        $office_location->battery_qty = $request->battery_qty;
        $office_location->installation_date = $request->installation_date;
        $office_location->warrenty_exipred_date = $request->warrenty_exipred_date;
        $office_location->amc_amount_per_month = $request->amc_amount_per_month;

        $office_location->active = $request->active ? 1 : 0;
        $office_location->asset = $request->asset ? 1 : 0;
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


    public function customerOfficeEdit(Request $request, OfficeLocation $office)
    {
        // menuSubmenu('officeLocation', 'createLocations');
        $divisions = DB::table('divisions')->select(['id', 'name'])->orderBy('name')->get();
        $districts = DB::table('districts')->select(['id', 'name', 'division_id'])->orderBy('name')->get();
        $thanas = DB::table('upazilas')->select(['id', 'name', 'district_id', 'division_id'])->orderBy('name')->get();
        $selected_districts = District::where('division_id', $office->division_id)->get();
        $selected_thanas = Upazila::where('district_id', $office->district_id)->get();
        $customer_companies = CustomerCompany::where('active', true)->get();
        return view('admin.location.customer_office.edit', compact('divisions', 'districts', 'thanas', 'customer_companies', 'office', 'selected_districts', 'selected_thanas'));
    }

    public function customerOfficeUpdate(Request $request, OfficeLocation $office)
    {
        $request->validate([
            'title' => 'required',
            'location' => 'nullable',
            'company' => 'required',
            'lat' => 'nullable',
            'lng' => 'nullable',
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
        $office->customer_company_id = $request->company;
        $office->address = $request->address;
        $office->serial_no = $request->serial_no;
        $office->start_date = $request->start_date;
        $office->end_date = $request->end_date;
        $office->amc_number = $request->amc_number;
        $office->billing_period = $request->billing_period;
        $office->item_code = $request->item_code;
        $office->location_type = $request->location_type;
        $office->booth_id = $request->booth_id;
        $office->booth_name = $request->booth_name;
        $office->mobile_number = $request->mobile_number;
        $office->ups_brand = $request->ups_brand;
        $office->model = $request->model;
        $office->capacity = $request->capacity;
        $office->battery_brand = $request->battery_brand;
        $office->battery_ah = $request->battery_ah;
        $office->battery_qty = $request->battery_qty;
        $office->installation_date = $request->installation_date;
        $office->warrenty_exipred_date = $request->warrenty_exipred_date;
        $office->amc_amount_per_month = $request->amc_amount_per_month;

        $office->asset = $request->asset ? 1 : 0;
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

    public function customerOfficeDelete(Request $request, OfficeLocation $office)
    {
        $office->delete();
        return redirect()->back()->with('success', 'Customer Office Location Deleted Successfully');
    }
}
