<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\OfficeLocation;
use App\Models\User;
use App\Models\UserLocation;
use App\Models\VisitPlan;
use Auth;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function trackingData(Request $request)
    {
        if (!$request->user || !$request->latitude || !$request->longitutde || !$request->timestamp) {
            return response()->json([
                'success' => false,
            ]);
        }
        $this->locationSet($request->user, $request->latitude, $request->longitutde, $request->timestamp);



        return response()->json([
            'success' => true,
        ], 200);
        $locations = json_decode($request->locations);
        foreach ($locations as  $location) {
            $user = User::find($location->user);
            if ($user) {
                $this->locationSet($location->user, $location->lat, $location->lng, $location->date);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "User Not Found"
                ]);
            }
        }
        // $lat = $lat;
        // $lng = $lng;
        // $date = $request->date;
        // $user = User::find($request->user);
    }
    public function locationSet($user, $lat, $lng, $date)
    {

        if ($user->track) {
            if ($lat and $lng) {
                //Attandance Tracking Start
                if ($user->officeLocation) {
                    if ($lat and $lng) {
                        $radius = .2;
                        $haversine = "(6371 * acos(cos(radians(" . $lat . "))
                                    * cos(radians(`lat`))
                                    * cos(radians(`lng`)
                                    - radians(" . $lng . "))
                                    + sin(radians(" . $lat . "))
                                    * sin(radians(`lat`))))";

                        $user_location = OfficeLocation::where('id', $user->officeLocation->id)
                            ->where('active', true)
                            ->where('type', 'company')
                            ->select('id', 'lat', 'lng', 'title')
                            ->whereRaw("{$haversine} < ?", [$radius])
                            ->selectRaw("{$haversine} AS distance")
                            ->latest()
                            ->orderBy('distance')
                            ->first();

                        if ($user_location) {
                            $attendance = Attendance::where('user_id', $user->id)
                                ->whereDate('date', Carbon::parse($date)->format('Y-m-d'))
                                ->first();

                            if ($attendance) {
                                $attendance->lat = $user_location->lat;
                                $attendance->lng = $user_location->lng;
                                $attendance->ip = request()->ip();
                                $attendance->logged_out_at = $date;
                                $attendance->save();
                            } else {
                                $attendance = new Attendance();
                                $attendance->user_id = $user->id;
                                $attendance->date = Carbon::parse($date)->format('Y-m-d');
                                $attendance->office_location_id = $user->officeLocation->id;
                                $attendance->company_id = $user->officeLocation->company_id;
                                $attendance->lat = $user_location->lat;
                                $attendance->lng = $user_location->lng;
                                $attendance->ip = request()->ip();
                                $attendance->logged_in_at = $date;
                                $attendance->logged_out_at = $date;
                                $attendance->save();

                                //Customized Date 21-08-2022
                                if ($attendance->office) {
                                    $attendance->office_start_time = $attendance->office->office_start_time;
                                    $attendance->office_end_time = $attendance->office->office_end_time;
                                    $attendance->office_late_time = $attendance->office->office_late_time;
                                    $attendance->office_absent_time = $attendance->office->office_absent_time;
                                }

                                if (Carbon::parse($attendance->logged_in_at)->format('H:m:s') > $attendance->office_start_time && Carbon::parse($attendance->logged_in_at)->format('H:m:s') <= $attendance->office_late_time) {
                                    $attendance->status = 'late_entry';
                                } elseif (Carbon::parse($attendance->logged_in_at)->format('H:m:s') > $attendance->office_late_time) {
                                    $attendance->status = 'absent';
                                } else {
                                    $attendance->status = 'present';
                                }

                                $attendance->save();
                                //Customized Date 21-08-2022
                            }
                        }
                    }
                }
                //Attandance Tracking End

                //for 3 minutes mute of data entry start
                // $oldLocation =  UserLocation::where('user_id', $user->id)->where('date', Carbon::parse($date)->format('Y-m-d'))->whereTime('created_at', '>', Carbon::parse($date)->subMinutes(2))->first();
                // if ($oldLocation) {
                //     return response()->json([
                //         'success' => false,

                //     ]);
                // }
                //for 3 minutes mute of data entry end


                $location = new UserLocation();
                $location->lat = $lat;
                $location->lng = $lng;
                $location->ip = request()->ip();
                $location->data_by_app = 1;
                $location->user_id = $user->id;
                $location->date = Carbon::parse($date)->format('Y-m-d');
                $location->addedBy_id = Auth::id();
                $location->save();

                // Office Location track Start
                if ($location->lat and $location->lng) {
                    $radius = .2;
                    $haversine = "(6371 * acos(cos(radians(" . $location->lat . "))
                                    * cos(radians(`lat`))
                                    * cos(radians(`lng`)
                                    - radians(" . $location->lng . "))
                                    + sin(radians(" . $location->lat . "))
                                    * sin(radians(`lat`))))";

                    $office_location = OfficeLocation::select('id', 'lat', 'lng', 'title', 'type')
                        ->where('active', true)
                        ->whereRaw("{$haversine} < ?", [$radius])
                        ->selectRaw("{$haversine} AS distance")
                        ->latest()
                        ->orderBy('distance')
                        ->first();
                    if ($office_location) {
                        $location->office_location_id = $office_location->id;
                        $location->save();

                        if ($user->employee && $office_location->type == 'customer') {

                            $vistiPlanAttandances = VisitPlan::whereDate('date_time', $date)
                                ->orderBy('date_time')
                                ->where('customer_office_location_id', $office_location->id)
                                ->where('employee_id', $user->employee->id)
                                ->first();

                            if ($vistiPlanAttandances) {
                                if ($vistiPlanAttandances->visit_start_at) {
                                    $vistiPlanAttandances->visit_end_at = $date;
                                    $vistiPlanAttandances->save();
                                } else {
                                    $vistiPlanAttandances->visit_start_at = $date;
                                    $vistiPlanAttandances->visit_end_at = $date;
                                    $vistiPlanAttandances->save();
                                }
                            }
                        }
                    }
                }
                // Office Location End

                //Poi Api Run in background for Location name START
                $this->Wo_RunInBackground(['lat' => $location->lat, 'lng' => $location->lng, 'success' => true,]);
                $this->locationNameSet($lat, $lng, $location);
                //Poi Api Run in background for Location name START

                return response()->json([
                    'success' => true,
                    'lat' => $location->lat,
                    'lng' => $location->lng,
                    'isApp' => 1
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'isApp' => 1
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'lat' => $lat,
                'lng' => $lng,
                'isApp' => 1
            ]);
        }
    }


    public function locationNameSet($lat, $lng, $location)
    {

        $url = url("http://poi.18gps.net/poi?lat={$lat}&lon={$lng}");
        $client = new Client();
        $r = $client->request('GET', $url);
        $result = $r->getBody()->getContents();
        $location->location = $result ?? null;
        $location->save();
        if (!$location->location) {
            $this->locationNameSet2($lat, $lng, $location);
        }
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

    public function locationNameSet2($lat, $lng, $location)
    {
        // $url = url("http://poi.18gps.net/poi?lat={$request->lat}&lon={$request->lng}");
        $url = url("http://open.mapquestapi.com/geocoding/v1/reverse?key=LGIyuPsyN7sbFV4FK8OZse4XzR2NZfu5&location={$lat},{$lng}&includeRoadMetadata=true&includeNearestIntersection=true");

        $client = new Client();
        $r = $client->request('GET', $url);

        $result = $r->getBody()->getContents();

        $result = json_decode($result);


        $locationObj = $result->results[0]->locations[0];

        $street =  $locationObj->street;
        $city = $locationObj->adminArea5;
        $postalCode = $locationObj->postalCode;
        $division = $locationObj->adminArea3;
        $country = $locationObj->adminArea1;

        $address = $street . ", " . $city . "-" . $postalCode . ", " . $division . ", " . $country . ".";

        $location->location = $result ?? '';
        $location->save();
    }
}
