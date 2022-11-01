<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\DesignationImport;
use App\Models\Designation;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DesignationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:designation-list', ['only' => ['index']]);
        $this->middleware('permission:designation-add', ['only' => ['store']]);
        $this->middleware('permission:designation-edit', ['only' => ['update']]);
        $this->middleware('permission:designation-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        menuSubmenu('designation', 'allDesignation');
        $designations = Designation::latest()->get();
        return view('admin.designation.designation', compact('designations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            $upload = Excel::import(new DesignationImport(), request()->file('file'),);
            if ($upload) {
                return redirect()->back()->with('success', "Designation Added Successfully");
            }
        }

        $request->validate([
            'designation'=>'required'
        ]);
        $designation = new Designation;
        $designation->title = $request->designation;
        $designation->save();
        return redirect()->back()->with('success', "Designation Added Successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function show(Designation $designation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function edit(Designation $designation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Designation $designation)
    {
        $request->validate([
            'designation'=>'required'
        ]);
        $designation->title = $request->designation;
        $designation->save();
        return redirect()->back()->with('success', "Designation Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Designation $designation)
    {
        $designation->delete();
        return redirect()->back()->with('success', "Designation Deleted Successfully");
    }
}
