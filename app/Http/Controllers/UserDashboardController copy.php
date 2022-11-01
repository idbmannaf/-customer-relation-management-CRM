<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Location;
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

class UserDashboardController extends Controller
{
    public function userDashboard(Request $request)
    {
        dd(1);
        $user = User::find($request->user);
        if ($user->id != Auth::id()) {
            return back();
        }

        return view('users.userDashboard', compact('user'));
    }
    public function viewUser(Request $request)
    {
        menuSubmenu('viewUser', 'viewUser');
        $user = User::find($request->user);
        if ($user->id != Auth::id()) {
            return back();
        }
        return view('users.view', compact('user'));
    }
    public function editUser(Request $request)
    {
        menuSubmenu('editUser', 'editUser');
        $user = User::find($request->user);
        if ($user->id != Auth::id()) {
            return back();
        }

        return view('users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);
        if ($request->type == 'user') {
            $request->validate([
                'name' => 'required',
                'email' => 'email|unique:users,email,' . $user->id,
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
            // $location = UserLocation::where('user_id',Auth::id())->where('created_at','>', now()->subMinutes(1))->latest()->first();


             if ($request->lat) {
                $location = new UserLocation;
                $location->lat = $request->lat;
                $location->lng = $request->lng;
                $location->ip = $request->ip();
                $location->user_id = $user->id;
                $location->addedBy_id = Auth::id();
                $location->save();

                if ($user->officeLocation) {
                    $radius = .2;
                    $locations = Location::get();
                    if (count($locations)) {
                        foreach ($locations as $location) {

                        }
                    }
                    $company_lat = $user->officeLocation->lat ?? 23.7508483;
                    $company_lng = $user->officeLocation->lng ?? 90.4031033;
                    if ($company_lat and $company_lng) {
                        $haversine = "(6371 * acos(cos(radians(" . $company_lat . "))
                                        * cos(radians(`lat`))
                                        * cos(radians(`lng`)
                                        - radians(" . $company_lng . "))
                                        + sin(radians(" . $company_lat . "))
                                        * sin(radians(`lat`))))";
                    }

                    $user_lcation = UserLocation::select('id', 'lat', 'lng')
                        ->where('user_id', $user->id)
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
                            $attendance->ip = $request->ip();
                            $attendance->logged_out_at = now();
                            $attendance->save();
                        } else {
                            $attendance = new Attendance;
                            $attendance->user_id = $user->id;
                            $attendance->date = date('Y-m-d');
                            $attendance->company_id = $user->officeLocation->company_id;
                            $attendance->lat = $user_lcation->lat;
                            $attendance->lng = $user_lcation->lng;
                            $attendance->ip = $request->ip();
                            $attendance->logged_in_at = now();
                            $attendance->logged_out_at = now();
                            $attendance->save();
                        }

                        $this->Wo_RunInBackground(['lat' => $location->lat, 'lng' => $location->lng, 'success' => true,]);
                        $this->locationNameSet($request, $location);
                        // $this->locationNameSet2($request, $location);
                        return response()->json([
                            'success' => true,
                            'lat' => $location->lat,
                            'lng' => $location->lng,
                        ]);
                    }
                    return response()->json([
                        'success' => false,
                        'lat' => $location->lat,
                        'lng' => $location->lng,
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'lat' => $location->lat,
                        'lng' => $location->lng,
                    ]);
                }
            }



        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }

    public function locationNameSet($request, $location)
    {
        $url = url("http://poi.18gps.net/poi?lat={$request->lat}&lon={$request->lng}");
        $client = new Client();
        $r = $client->request('GET', $url);
        $result = $r->getBody()->getContents();
        $location->location = $result ?? null;
        $location->save();
        if(!$location->location){
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
}
