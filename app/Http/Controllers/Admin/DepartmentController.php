<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\DepartmentImport;
use App\Models\Department;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:department-list', ['only' => ['index']]);
        $this->middleware('permission:department-add', ['only' => ['store']]);
        $this->middleware('permission:department-edit', ['only' => ['update']]);
        $this->middleware('permission:department-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       menuSubmenu('department','allDepartment');
       $departments = Department::latest()->paginate(30);
       return view('admin.department.department',compact('departments'));
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
            $upload = Excel::import(new DepartmentImport(), request()->file('file'),);
            if ($upload) {
                return redirect()->back()->with('success', "Department Added Successfully");
            }
        }
        $request->validate([
            'department'=>'required'
        ]);
        $department = new Department;
        $department->title = $request->department;
        $department->save();
        return redirect()->back()->with('success', "Department Added Successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        $request->validate([
            'department'=>'required'
        ]);
        $department->title = $request->department;
        $department->save();
        return redirect()->back()->with('success', "Department Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
       $department->delete();
       return redirect()->back()->with('success', "Department Deleted Successfully");
    }
}
