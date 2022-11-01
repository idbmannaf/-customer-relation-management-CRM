<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Auth;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        menuSubmenu('holidays', 'all_holidays');
        $this_year = $request->year ?? date("Y");
        $holidays = Holiday::where('year',$this_year)->paginate(50);
        return view('admin.holidays.holidays',compact('this_year','holidays'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.holidays.create_holiday');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'date.*'=>'required',
        //     'date'=>'required'
        // ]);
        $holidays = $request->date;
        foreach ($holidays as  $holidate) {
            $oldHoliday = Holiday::where('date',$holidate)->first();
            if (!$oldHoliday) {
              $year = explode('-',$holidate)[0];
              $holiday = new Holiday;
              $holiday->date = $holidate;
              $holiday->year = $year;
              $holiday->purpose = $request->purpose;
              $holiday->addedBy_id = Auth::id();
              $holiday->save();
            }
        }
        return back()->with('success','Holiday Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function show(Holiday $holiday)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Holiday $holiday)
    {


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Holiday $holiday)
    {
        $request->validate([
            'date'=>'required'
        ]);
        $holiday->date = $request->date;
        $year = explode('-',$request->date)[0];
        $holiday->year = $year;
        $holiday->purpose = $request->purpose;
        $holiday->save();
        return back()->with('success','Holiday Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function destroy(Holiday $holiday)
    {
        //
    }
}
