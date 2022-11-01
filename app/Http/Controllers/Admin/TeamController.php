<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Team;
use App\Models\TeamRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        menuSubmenu('teams', 'allTeam');
        $teams = Team::with('company','team_roles')->latest()->paginate(20);
        $companies = Company::where('active',true)->get();
        return view('admin.teams.index', compact('teams','companies'));
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
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'company' => 'required',
            'active' => 'nullable',
        ]);
        $team = new Team;
        $team->name = $request->name;
        $team->company_id = $request->company;
        $team->description = $request->description;
        $team->active = $request->active ? 1 : 0;
        $team->addedBy_id = Auth::id();
        $team->save();

        // $user = User::find($request->user);
        // $teamAdmin = TeamRole::where('team_id', $team->id)
        //     ->where('user_id', $user->id)
        //     ->first();

        // if (!$teamAdmin) {
        //     $teamAdmin = new TeamRole;
        //     $teamAdmin->user_id = $user->id;
        //     $teamAdmin->team_id = $team->id;
        //     $teamAdmin->role_name = 'admin';
        //     $teamAdmin->active = true;
        //     $teamAdmin->save();
        // }

        return redirect()->back()->with('success', ' Team Added successfuly');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function edit(Team $team)
    {
        // return view('admin.teams.edit',compact('team'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'company' => 'required',
            'active' => 'nullable',
        ]);
        $team->name = $request->name;
        $team->company_id = $request->company;
        $team->description = $request->description;
        $team->active = $request->active ? 1 : 0;
        $team->editedBy_id = Auth::id();
        $team->save();
        return redirect()->back()->with('success', ' Company Updated successfuly');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        $team->delete();
        return redirect()->back()->with('success', ' Company Updated successfuly');
    }
    public function teamRoles(Team $team, Request $request)
    {
        if ($request->ajax()) {
            return view('admin.teams.ajax.memberAjax',compact('team'))->render();
        }
        return view('admin.teams.teamRoles', compact('team'));
    }
    public function roleDelete(Team $team, Request $request)
    {
        $role = TeamRole::find($request->role);
        $role->delete();
        return response()->json(['success' => true]);
    }
    public function addTeamRole(Team $team, Request $request)
    {
        $user = User::find($request->user);
        if (!$team->hasAnyRole($user->id)) {
           $teamRole = new TeamRole;
           $teamRole->role_name = $request->type;
           $teamRole->user_id = $user->id;
           $teamRole->team_id = $team->id;
           $teamRole->save();
           return redirect()->back()->with('success',"New Role successfully Added");
        }else {
            return redirect()->back()->with('warning','This User have Already Roles');
        }
    }
}
