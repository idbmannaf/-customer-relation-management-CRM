<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Attendance;
use App\Models\Company;
use App\Models\OfficeLocation;
use App\Models\Team;
use App\Models\TeamRole;
use App\Models\User;
use App\Models\UserCompany;
use App\Models\UserLocation;
use App\Models\UserTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user-list', ['only' => ['index']]);
        $this->middleware('permission:user-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
        $this->middleware('permission:user-location', ['only' => ['location']]);
        $this->middleware('permission:user-with-roles', ['only' => ['userWithRoles']]);
        $this->middleware('permission:user-assign-role', ['only' => ['assignRole']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        menuSubmenu('users', 'allUsers');
        $users = User::with('permissions', 'roles', 'customer')->latest()->paginate(30);
        $roles = Role::all();
        $q = '';
        if ($request->ajax()) {
            return  view('admin.users.ajax.userList', compact('users', 'roles', 'q'))->render();
        }

        return view('admin.users.index', compact('users', 'roles', 'q'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        menuSubmenu('users', 'createUser');
        $roles = Role::latest()->get();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'password' => 'required|confirmed|min:5',
        ]);
        $user = new User;
        $user->name = $request->name;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->track = $request->track_him ? 1 : 0;
        $user->temp_password = $request->password;
        if ($file = $request->avater) {
            $extension = $file->getClientOriginalExtension();
            $randomFileName = $user->id . Str::slug($user->name ?? 'No Name Found') . '_banner_' . date('Y_m_d_his') . '_' . rand(100, 999) . '.' . $extension;
            Storage::disk('public')->put('users/' . $randomFileName, File::get($file));
            $user->avater = $randomFileName;
        }
        $user->save();

        if ($request->role) {
            $user->syncRoles([$request->role]);
        }
        return redirect()->back()->with('success', 'User Created Successfully');
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
        $user = User::find($id);
        $companies = Company::where('active', true)->get();
        $office_locations = OfficeLocation::where('active', true)->get();
        return view('admin.users.edit', compact('user', 'companies', 'office_locations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $user = User::find($id);
        if ($request->type == 'user') {
            $request->validate([
                'name' => 'required',
                // 'username' => 'required|unique:users,username,' . $user->id,
            ]);

            $user->name = $request->name;
            $user->track = $request->track_him ? 1 : 0;
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
                'password' => 'required|confirmed|min:5'
            ]);

            $user->password = Hash::make($request->password);
            $user->temp_password = $request->password;
            $user->save();
        }

        return redirect()->back()->with('success', "{$request->type} Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if ($user->id == Auth::id()) {
            return redirect()->back()->with('error', 'Sorry!!!. You are not able to delete your own Account');
        }
        $user->delete();
        return redirect()->back()->with('success', 'User Deleted Successfully');
    }
    public function location(Request $request, User $user)
    {
        $locations = UserLocation::with('Office')->where('user_id', $user->id)->where('office_location_id', "<>", null)->latest()->paginate(100);
        return view('admin.users.location', compact('locations', 'user'));
    }
    public function userUpdate(Request $request, User $user)
    {

        $request->validate([
            'company' => 'required',
            'team' => 'required',
            'role' => 'required'
        ]);
        $company = Company::find($request->company);
        $team = Team::find($request->team);

        $type = $request->type;
        if ($type == 'team_role') {
            $teamRole = TeamRole::where('user_id', $user->id)->where('team_id', $team->id)->first();
            if ($teamRole) {
                $teamRole->role_name = $request->role;
                $teamRole->save();
                return redirect()->back()->with('success', "Role Updated Successfully");
            } else {
                $teamRole = new TeamRole;
                $teamRole->user_id = $user->id;
                $teamRole->team_id = $team->id;
                $teamRole->role_name = $request->role;
                $teamRole->save();
                return redirect()->back()->with('success', "Role Added Successfully");
            }
        }
        //     if () {
        //         # code...
        //     }
        //     $user->companies()->detach();
        //     if ($request->companies) {
        //       foreach ($request->companies as  $company_id) {
        //          $user_company = new UserCompany;
        //          $user_company->user_id = $user->id;
        //          $user_company->company_id = $company_id;
        //          $user_company->addedBy_id = Auth::id();
        //          $user_company->save();
        //       }
        //     }
        // }if ($type == 'team') {
        //     $user->teams()->detach();
        //     if ($request->teams) {
        //       foreach ($request->teams as  $team_id) {
        //          $user_team = new UserTeam;
        //          $user_team->user_id = $user->id;
        //          $user_team->team_id = $team_id;
        //          $user_team->addedBy_id = Auth::id();
        //          $user_team->save();
        //       }
        //     }
        // }
        // return redirect()->back()->with('success',"User {$type} Updated Successfully");
    }
    public function selectUser(Request $request)
    {
        $q = $request->q;

        $users = User::where(function ($query) use ($q) {

            $query->where('name', 'like', '%' . $q . '%');
            $query->orWhere('email', 'like', '%' . $q . '%');
        })
            ->where('active', true)
            ->select(['id', 'email', 'name'])
            ->take(30)
            ->get();
        if ($users->count()) {
            if ($request->ajax()) {
                // return Response()->json(['items'=>$users]);
                return $users;
            }
        } else {
            if ($request->ajax()) {
                return $users;
            }
        }
    }
    public function CompanyTeams(Request $request)
    {
        $user = User::find($request->user);
        $company = Company::find($request->company);
        if ($request->type == 'company') {
            return view('admin.users.ajax.showTeams', compact('company', 'user'))->render();
        }
        if ($request->type == 'team') {
            $team = Team::find($request->team);
            return view('admin.users.ajax.showTeamsRole', compact('company', 'team', 'user'))->render();
        }
    }
    public function attaendance(User $user)
    {
        $attendances = Attendance::where('user_id', $user->id)->latest()->paginate(30);
        return view('admin.users.attendance', compact('user', 'attendances'));
    }
    public function searchUser(Request $request)
    {
        $q = $request->q;
        $users = User::where('username', 'like', "%" . $q . "%")
            ->orWhere('name', 'like', "%" . $q . "%")
            ->paginate(30);

        $roles = Role::all();
        return  view('admin.users.ajax.userList', compact('users', 'roles', 'q'))->render();
    }
    public function userWithRoles(Request $request)
    {
        $users = User::has('roles')->with('roles')->paginate(30);
        $roles = Role::all();
        return view('admin.users.userWithRoles', compact('users', 'roles'));
    }
    public function assignRole(User $user, Request $request)
    {


        if ($request->isMethod('post')) {

            if($request->role_name == 'no_role'){
               $user->roles()->detach();
            }else{
                if ($user->customer) {
                    return redirect()->back()->with('error', 'This User Already a Customer');
                }

                if ($user->hasRole($request->role_name)) {
                    return redirect()->back()->with('error', "This User Already a {$request->role_name}");
                }

                $user->syncRoles($request->role_name);
            }

        } else {

            $roles = Role::all();
            return view('admin.users.userRoles', compact('user', 'roles'));
        }
        return back();
    }
}
