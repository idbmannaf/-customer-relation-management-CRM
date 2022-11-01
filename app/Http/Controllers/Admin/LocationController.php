<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\District;
use App\Models\Employee;
use App\Models\Location;
use App\Models\Team;
use App\Models\Upazila;
use App\Models\User;
use App\Models\UserLocation;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class LocationController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:attendance-today', ['only' => ['attendance']]);
        $this->middleware('permission:attendance-history', ['only' => ['attendanceHistory']]);
        $this->middleware('permission:attendance-report', ['only' => ['attendanceReport']]);
    }
    public function attendance(Request $request)
    {
        menuSubmenu('attendance', 'allAttendance');
        $attendances = Attendance::with('company')->whereDate('date', Carbon::now()->format('Y-m-d'))
            ->paginate(100);
        return view('admin.attendance.index', compact('attendances'));
    }
    public function setAttendance(Request $request, User $user)
    {

        $team_ids = $user->team_roles->pluck('team_id');
        $teams = Team::whereIn('id', $team_ids)->groupBy('company_id')->get();
        foreach ($teams as $team) {
            $radius = .2;
            $company_lat = $team->company->lat ?? 23.7614067;
            $company_lng = $team->company->lng ?? 90.4195412;
            if ($company_lat and $company_lng) {
                $haversine = "(6371 * acos(cos(radians(" . $company_lat . "))
                                * cos(radians(`lat`))
                                * cos(radians(`lng`)
                                - radians(" . $company_lng . "))
                                + sin(radians(" . $company_lat . "))
                                * sin(radians(`lat`))))";
            }

            $user_lcation = UserLocation::select('id', 'lat', 'lng')
                ->where('id', $user->id)
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
                    $attendance->logged_out_at = now();
                    $attendance->save();
                } else {
                    $attendance = new Attendance;
                    $attendance->user_id = $user->id;
                    $attendance->date = date('Y-m-d');
                    $attendance->company_id = $team->company_id;
                    $attendance->lat = $user_lcation->lat;
                    $attendance->lng = $user_lcation->lng;
                    $attendance->logged_in_at = now();
                    $attendance->logged_out_at = now();
                    $attendance->save();
                }
                return response()->json([
                    'success' => true,
                ]);
                break;
            }
            return response()->json([
                'success' => false,
            ]);
        }
    }


    public function attendanceHistory(Request $request)
    {
        menuSubmenu('attendance', 'attendanceHistory');
        $attendances = Attendance::with('company')->orderBy('date', 'DESC')->paginate(50);

        return view('admin.attendance.attandanceHistory', compact('attendances'));
    }
    public function attendanceReport(Request $request)
    {
        menuSubmenu('attendance', 'attendanceReport');
        // if (isset($request->from) or isset($request->to)) {
        //     $validator = Validator::make($request->all(), [
        //         'from' => 'date|before:to',
        //         'to' => 'date|before:tomorrow',
        //     ]);

        //     if ($validator->fails()) {
        //         return redirect()
        //             ->back()
        //             ->withErrors($validator)
        //             ->withInput();
        //     }
        // }
        if ($request->type == 'filter') {

            $attendances = Attendance::where(function ($query) use ($request) {
                if ($request->s_date) {
                    $query->where('created_at', '>', $request->s_date);
                    // $query->where('created_at', '<=', now())->where('created_at', '>=', now()->subDays(1));
                } elseif ($request->e_date) {
                    $query->where('created_at', '<', $request->s_date);
                    // $query->where('created_at', '<=', now())->where('created_at', '>=', now()->subDays(1));
                } elseif ($request->s_date && $request->e_date) {
                    $query->where('created_at', '<=', now()->parse($request->s_date))->where('created_at', '>=', now()->parse($request->e_date));
                }
                if ($request->employee) {
                    $employee = Employee::where('id', $request->employee)->first();
                    // dd( $employee->user_id);
                    $query->where('user_id', $employee->user_id);
                }
            })->latest()->paginate(100);
            if ($request->employee) {
                $employee = Employee::find($request->employee);
                return view('admin.attendance.attandanceReport', [
                    'type' => $request->type,
                    'employee' => $employee,
                    'attendances' => $attendances,
                    'input' => $request->all(),
                ]);
            } else {
                return view('admin.attendance.attandanceReport', [
                    'type' => $request->type,
                    'attendances' => $attendances,
                    'input' => $request->all(),
                ]);
            }
        }
        return view('admin.attendance.attandanceReport', [
            'input' => $request->all(),
        ]);
    }
    public function index()
    {
        menuSubmenu('glocation', 'glocation');
        $locations = Location::latest()->paginate(50);
        return view('admin.location.index', compact('locations'));
    }
    public function create()
    {
      return view('admin.location.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ]);

        $location = new Location;
        $location->name = $request->name;
        $location->lat = $request->lat;
        $location->lng = $request->lng;
        $location->save();
        return redirect()->back()->with('success', 'Location Added Successfully');
    }
    public function edit(Request $request, Location $location)
    {
        return view('admin.location.edit',compact('location'));
    }
    public function update(Request $request, Location $location)
    {
        $request->validate([
            'name' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ]);
        $location->name = $request->name;
        $location->lat = $request->lat;
        $location->lng = $request->lng;
        $location->save();
        return redirect()->back()->with('success', 'Location Updated Successfully');
    }
    public function destroy(Location $location)
    {
        $location->delete();
        return redirect()->back()->with('success', 'Location Deleted Successfully');
    }
    public function locationTest()
    {
        return view('locationTest');
    }
}
