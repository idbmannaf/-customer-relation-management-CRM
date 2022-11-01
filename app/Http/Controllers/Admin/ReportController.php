<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CollectionHistory;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    //Attandance Report
    public function attendanceReport(Request $request)
    {

    }
    public function collectionReport(Request $request)
    {
        menuSubmenu('reports','collectionReport');
        $c_type = $request->c_type;
        $from = $request->from;
        $to = $request->to;
        if ($c_type || $from || $to) {
            $collections = CollectionHistory::orderBy('created_at');
            if ($c_type == 'paid') {
                $collections = $collections->where('purpose','minus');
            }elseif ($c_type == 'plus') {

                $collections = $collections->where('purpose','plus');
            }
            if ($from && $to) {
                $collections = $collections->whereBetween('created_at',[$from." 00:00:00", $to." 00:00:00"]);
            }elseif ($from) {
                $collections = $collections->whereDate('created_at',$from." 00:00:00");
            }elseif ($to) {
                return redirect()->back()->with('warning','first Select Form Date');
            }
           $collections= $collections->paginate(100);

            return view('admin.report.collection_report',compact('collections'));
        }
        return view('admin.report.collection_report');
    }
}
