<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\CompanyImport;
use App\Models\Company;
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

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:company-list', ['only' => ['index']]);
        $this->middleware('permission:company-add', ['only' => ['store']]);
        $this->middleware('permission:company-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:company-delete', ['only' => ['destroy']]);
        $this->middleware('permission:company-offices', ['only' => ['companyOffice']]);
        $this->middleware('permission:company-customers', ['only' => ['companyCustomers']]);
        $this->middleware('permission:company-employees', ['only' => ['companyEmployee']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        menuSubmenu('companies', 'allCompanies');
        $companies = Company::latest()->paginate(20);
        return view('admin.companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        menuSubmenu('companies', 'createCompany');
        return view('admin.companies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->type == 'bulk') {
            $request->validate([
                'file' => 'required'
            ]);
            if ($request->file->getClientOriginalExtension() != 'xlsx') {
                return redirect()->back()->with('error', "Only xlsx File Allowed");
            }
            $upload = Excel::import(new CompanyImport(), request()->file('file'),);
            if ($upload) {
                return redirect()->back()->with('success', "Designation Added Successfully");
            }
        }
        $request->validate([
            'name' => 'required',
            'team_head_access_all_customers' => 'nullable',
            'team_member_access_all_customers' => 'nullable',
            'account_maintain_permission' => 'nullable',
            'store_damage_product_assign_permission' => 'nullable',
            'active' => 'nullable',
        ]);
        $company = new Company;
        $company->name = $request->name;
        $company->address = $request->address;
        $company->team_head_access_all_customers = $request->team_head_access_all_customers ? 1 : 0;
        $company->team_member_access_all_customers = $request->team_member_access_all_customers ? 1 : 0;
        $company->logo_and_req_permission = $request->logo_and_req_permission ? 1 : 0;
        $company->inventory_maintain_permission = $request->inventory_maintain_permission ? 1 : 0;
        $company->account_maintain_permission = $request->account_maintain_permission ? 1 : 0;
        $company->access_all_call = $request->access_all_call ? 1 : 0;
        $company->access_all_call_visit_plan_without_call = $request->access_all_call_visit_plan_without_call ? 1 : 0;
        $company->store_damage_product_assign_permission = $request->store_damage_product_assign_permission ? 1 : 0;
        $company->active = $request->active ? 1 : 0;
        $company->addedBy_id = Auth::id();
        $company->save();
        return redirect()->back()->with('success', ' Company Added successfuly');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        return view('admin.companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        // dd($request->inventory_maintain_permission);
        $request->validate([
            'name' => 'required',
            'team_head_access_all_customers' => 'nullable',
            'team_member_access_all_customers' => 'nullable',
            'account_maintain_permission' => 'nullable',
            'store_damage_product_assign_permission' => 'nullable',
            'active' => 'nullable',
        ]);
        $company->name = $request->name;
        $company->address = $request->address;
        $company->team_head_access_all_customers = $request->team_head_access_all_customers ? 1 : 0;
        $company->team_member_access_all_customers = $request->team_member_access_all_customers ? 1 : 0;
        $company->logo_and_req_permission = $request->logo_and_req_permission ? 1 : 0;
        $company->inventory_maintain_permission = $request->inventory_maintain_permission ? 1 : 0;
        $company->account_maintain_permission = $request->account_maintain_permission ? 1 : 0;
        $company->access_all_call = $request->access_all_call ? 1 : 0;
        $company->access_all_call_visit_plan_without_call = $request->access_all_call_visit_plan_without_call ? 1 : 0;
        $company->store_damage_product_assign_permission = $request->store_damage_product_assign_permission ? 1 : 0;
        $company->active = $request->active ? 1 : 0;
        $company->editedBy_id = Auth::id();
        $company->save();

        return redirect()->back()->with('success', ' Company Updated successfuly');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        if ($company->hasAnyCustomers()) {
            return redirect()->back()->with('error', 'You are not able to delete this company.');
        } else {

            $company->delete();
            return redirect()->back()->with('success', ' Company Deleted successfuly');
        }
    }
    public function companyCustomers(Request $request, Company $company)
    {
        return view('admin.companies.customers', compact('company'));
    }

    public function companyOffice(Request $request, Company $company)
    {
        return view('admin.companies.offices.offices', compact('company'));
    }

    public function companyOfficeCreate(Request $request, Company $company)
    {
        $divisions = DB::table('divisions')->select(['id', 'name'])->orderBy('name')->get();
        $districts = DB::table('districts')->select(['id', 'name', 'division_id'])->orderBy('name')->get();
        $thanas = DB::table('upazilas')->select(['id', 'name', 'district_id', 'division_id'])->orderBy('name')->get();
        return view('admin.companies.offices.create', compact('divisions', 'districts', 'thanas', 'company'));
    }
    public function companyOfficeStore(Request $request, Company $company)
    {
        $request->validate([
            'title' => 'required',
            'location' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'division' => 'nullable',
            'district' => 'nullable',
            'thana' => 'nullable',
            'office_start_time' => 'required',
            'office_end_time' => 'required',
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
        $office_location->company_id = $company->id;
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
    public function companyOfficeEdit(Request $request, Company $company, OfficeLocation $office)
    {
        $divisions = DB::table('divisions')->select(['id', 'name'])->orderBy('name')->get();
        $districts = DB::table('districts')->select(['id', 'name', 'division_id'])->orderBy('name')->get();
        $thanas = DB::table('upazilas')->select(['id', 'name', 'district_id', 'division_id'])->orderBy('name')->get();
        $selected_districts = District::where('division_id', $office->division_id)->get();
        $selected_thanas = Upazila::where('district_id', $office->district_id)->get();
        return view('admin.companies.offices.edit', compact('office', 'divisions', 'districts', 'thanas', 'selected_districts', 'selected_thanas', 'company'));
    }
    public function companyOfficeUpdate(Request $request, Company $company, OfficeLocation $office)
    {
        $request->validate([
            'title' => 'required',
            'location' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'division' => 'nullable',
            'district' => 'nullable',
            'thana' => 'nullable',
            'office_start_time' => 'required',
            'office_end_time' => 'required',
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
        $office->active = $request->active ? 1 : 0;
        $office->editedBy_id = Auth::id();
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
        return redirect()->back()->with('success', 'Office Location Added Successfully');
    }
    public function companyOfficeDelete(Request $request,Company $company, OfficeLocation $office)
    {
        $office->delete();
        return redirect()->back()->with('success', 'Company Office Location Deleted Successfully');
    }

    public function companyEmployee(Request $request, Company $company)
    {
        $employees = $company->employees;
        return view('admin.companies.employees', compact('company', 'employees'));
    }
}
