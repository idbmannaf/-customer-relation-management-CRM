<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Location;
use App\Models\OfficeLocation;
use App\Models\Team;
use App\Models\User;
use App\Models\UserLocation;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Locale;

class UserDashboardController extends Controller
{
    public function userDashboard(Request $request)
    {
        // dd(User::role(['admin' , 'moderator'])->get());
        menuSubmenu('viewUser', 'viewUser');
        $user = Auth::user();
        return view('users.userDashboard', compact('user'));
    }
    public function viewUser(Request $request)
    {
        menuSubmenu('viewUser', 'viewUser');
        $user = Auth::user();
        return view('users.view', compact('user'));
    }
    public function editUser(Request $request)
    {
        menuSubmenu('editUser', 'editUser');
        $user = Auth::user();
        return view('users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);
        if ($request->type == 'user') {
            $request->validate([
                'name' => 'required',
                'image' => 'nullable |image|mimes:png,jpg'
            ]);
            $user->name = $request->name;
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
                return redirect()->back()->with('warning', 'Old Password Dose not matched');
            }
        }

        return redirect()->back()->with('success', "{$request->type} Updated Successfully");
    }
    public function locationSet(Request $request)
    {
        $user = Auth::user();
        if ($user->track) {
            if ($request->lat and $request->lng) {
                //Attandance Tracking Start
                if ($user->officeLocation) {
                    
                    if ($request->lat and $request->lng) {
                        $radius = .3;
                        $haversine = "(6371 * acos(cos(radians(" . $request->lat . "))
                                    * cos(radians(`lat`))
                                    * cos(radians(`lng`)
                                    - radians(" . $request->lng . "))
                                    + sin(radians(" . $request->lat . "))
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
                            
                    // dd($user_location);
                            
// $company_id = $user_location->officeLocation->company_id;
                        if ($user_location) {
                            $attendance = Attendance::where('user_id', $user->id)
                                ->whereDate('date', Carbon::now()->format('Y-m-d'))
                                ->first();
                            if ($attendance) {
                                $attendance->lat = $user_location->lat;
                                $attendance->lng = $user_location->lng;
                                $attendance->ip = $request->ip();
                                $attendance->logged_out_at = now();
                                $attendance->save();
                            } else {
                                $attendance = new Attendance;
                                $attendance->user_id = $user->id;
                                $attendance->date = date('Y-m-d');
                                $attendance->office_location_id =$user->officeLocation->id;
                                $attendance->company_id = $user->officeLocation->company_id;
                                $attendance->lat = $user_location->lat;
                                $attendance->lng = $user_location->lng;
                                $attendance->ip = $request->ip();
                                $attendance->logged_in_at = now();
                                $attendance->logged_out_at = now();
                                $attendance->save();
                            }
                        }
                    }
                }
                //Attandance Tracking End


                //for 3 minutes mute of data entry start
                $oldLocation =  UserLocation::where('user_id', $user->id)->where('date', date('Y-m-d'))->whereTime('created_at', '>', Carbon::now()->subMinutes(2))->first();
                if ($oldLocation) {
                    return response()->json([
                        'success' => false,
                    ]);
                }
                //for 3 minutes mute of data entry end



                $location = new UserLocation;
                $location->lat = $request->lat;
                $location->lng = $request->lng;
                $location->ip = $request->ip();
                $location->user_id = $user->id;
                $location->date = date('Y-m-d');
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

                    $office_location = OfficeLocation::select('id', 'lat', 'lng', 'title')
                        ->where('active', true)
                        ->whereRaw("{$haversine} < ?", [$radius])
                        ->selectRaw("{$haversine} AS distance")
                        ->latest()
                        ->orderBy('distance')
                        ->first();
                    if ($office_location) {
                        $location->office_location_id = $office_location->id;
                        $location->save();
                    }
                }
                // Office Location End

                //Poi Api Run in background for Location name START
                $this->Wo_RunInBackground(['lat' => $location->lat, 'lng' => $location->lng, 'success' => true,]);
                $this->locationNameSet($request, $location);
                //Poi Api Run in background for Location name START

                return response()->json([
                    'success' => true,
                    'lat' => $location->lat,
                    'lng' => $location->lng,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                ]);
            }
        }
    }

    // public function locationSet(Request $request)
    // {
    //     $user = Auth::user();
    //     if ($user->track) {
    //         if ($request->lat and $request->lng) {
    //             $location = new UserLocation;
    //             $location->lat = $request->lat;
    //             $location->lng = $request->lng;
    //             $location->ip = $request->ip();
    //             $location->user_id = $user->id;
    //             $location->date = date('Y-m-d');
    //             $location->addedBy_id = Auth::id();
    //             $location->save();

    //             // Office Location track Start
    //             if ($location->lat and $location->lng) {
    //                 $radius = .2;
    //                 $haversine = "(6371 * acos(cos(radians(" . $location->lat . "))
    //                                 * cos(radians(`lat`))
    //                                 * cos(radians(`lng`)
    //                                 - radians(" . $location->lng . "))
    //                                 + sin(radians(" . $location->lat . "))
    //                                 * sin(radians(`lat`))))";

    //                 $office_location = OfficeLocation::select('id', 'lat', 'lng', 'title')
    //                     ->where('active', true)
    //                     ->whereRaw("{$haversine} < ?", [$radius])
    //                     ->selectRaw("{$haversine} AS distance")
    //                     ->latest()
    //                     ->orderBy('distance')
    //                     ->first();
    //                 if ($office_location) {
    //                     $location->office_location_id = $office_location->id;
    //                     $location->save();
    //                 }
    //             }
    //             // Office Location End


    //             //Attandance Tracking Start

    //             if ($user->officeLocation) {
    //                 if ($location->lat and $location->lng) {
    //                     $radius = .2;
    //                     $haversine = "(6371 * acos(cos(radians(" . $location->lat . "))
    //                                 * cos(radians(`lat`))
    //                                 * cos(radians(`lng`)
    //                                 - radians(" . $location->lng . "))
    //                                 + sin(radians(" . $location->lat . "))
    //                                 * sin(radians(`lat`))))";

    //                     $user_location = OfficeLocation::where('id', $user->officeLocation->id)
    //                         ->where('active', true)
    //                         ->where('type', 'company')
    //                         ->select('id', 'lat', 'lng', 'title')
    //                         ->whereRaw("{$haversine} < ?", [$radius])
    //                         ->selectRaw("{$haversine} AS distance")
    //                         ->latest()
    //                         ->orderBy('distance')
    //                         ->first();

    //                     if ($user_location) {
    //                         $attendance = Attendance::where('user_id', $user->id)
    //                             ->whereDate('date', Carbon::now()->format('Y-m-d'))
    //                             ->first();

    //                         if ($attendance) {
    //                             $attendance->lat = $user_location->lat;
    //                             $attendance->lng = $user_location->lng;
    //                             $attendance->ip = $request->ip();
    //                             $attendance->logged_out_at = now();
    //                             $attendance->save();
    //                         } else {
    //                             $attendance = new Attendance;
    //                             $attendance->user_id = $user->id;
    //                             $attendance->date = date('Y-m-d');
    //                             $attendance->company_id = $user->officeLocation->company_id;
    //                             $attendance->lat = $user_location->lat;
    //                             $attendance->lng = $user_location->lng;
    //                             $attendance->ip = $request->ip();
    //                             $attendance->logged_in_at = now();
    //                             $attendance->logged_out_at = now();
    //                             $attendance->save();
    //                         }
    //                     }

    //                     $this->Wo_RunInBackground(['lat' => $location->lat, 'lng' => $location->lng, 'success' => true,]);
    //                     $this->locationNameSet($request, $location);

    //                     return response()->json([
    //                         'success' => true,
    //                         'lat' => $location->lat,
    //                         'lng' => $location->lng,
    //                     ]);
    //                 }
    //             }
    //         } else {
    //             return response()->json([
    //                 'success' => false,
    //             ]);
    //         }
    //     }
    // }

    public function locationNameSet($request, $location)
    {
        $user = Auth::user();
        // $global_locations = Location::all();

        // if (count($global_locations)) {
        //     $radius = .2;
        //     foreach ($global_locations as  $global_location) {
        //         $lat = $global_location->lat ?? 23.7508483;
        //         $lng = $global_location->lng ?? 90.4031033;
        //         if ($lat and $lng) {
        //             $haversine = "(6371 * acos(cos(radians(" . $lat . "))
        //                             * cos(radians(`lat`))
        //                             * cos(radians(`lng`)
        //                             - radians(" . $lng . "))
        //                             + sin(radians(" . $lat . "))
        //                             * sin(radians(`lat`))))";
        //         }
        //         $user_location = UserLocation::select('id', 'lat', 'lng')
        //             ->where('user_id', $user->id)
        //             ->whereRaw("{$haversine} < ?", [$radius])
        //             ->selectRaw("{$haversine} AS distance")
        //             ->latest()
        //             ->orderBy('distance')
        //             ->first();
        //         if ($user_location) {
        //             $location->location = $global_location->name;
        //             $location->save();
        //             return;
        //         }
        //     }
        // }

        $url = url("http://poi.18gps.net/poi?lat={$request->lat}&lon={$request->lng}");
        $client = new Client();
        $r = $client->request('GET', $url);
        $result = $r->getBody()->getContents();
        $location->location = $result ?? null;
        $location->save();
        if (!$location->location) {
            $this->locationNameSet2($request, $location);
        }
    }


    public function locationNameSet2($request, $location)
    {
        // $url = url("http://poi.18gps.net/poi?lat={$request->lat}&lon={$request->lng}");
        $url = url("http://open.mapquestapi.com/geocoding/v1/reverse?key=LGIyuPsyN7sbFV4FK8OZse4XzR2NZfu5&location={$request->lat},{$request->lng}&includeRoadMetadata=true&includeNearestIntersection=true");

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

    public function globalLocationTrack($request, $user)
    {
        $gLocations = Location::all();
        foreach ($gLocations as $gLocation) {

            $radius = .2;
            $gLocation_lat = $gLocation->lat ?? 23.7508483;
            $gLocation_lng = $gLocation->lng ?? 90.4031033;
            if ($gLocation_lat and $gLocation_lng) {
                $haversine = "(6371 * acos(cos(radians(" . $gLocation_lat . "))
                                * cos(radians(`lat`))
                                * cos(radians(`lng`)
                                - radians(" . $gLocation_lng . "))
                                + sin(radians(" . $gLocation_lat . "))
                                * sin(radians(`lat`))))";
            }

            $user_location = UserLocation::where('user_id', $user->id)
                ->whereRaw("{$haversine} < ?", [$radius])
                ->selectRaw("{$haversine} AS distance")
                ->latest()
                ->orderBy('distance')
                ->first();
            if ($user_location) {
                $user_location->lat = $gLocation->lat;
                $user_location->lng = $gLocation->lng;
                $user_location->Location = $gLocation->name;
                $user_location->save();
                return $user_location;
            } else {
                return false;
            }
        }
    }
}
