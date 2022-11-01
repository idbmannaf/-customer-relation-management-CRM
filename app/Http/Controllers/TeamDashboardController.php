<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeamDashboardController extends Controller
{
    public function __constract()
    {
       $this->middleware('teamRole:team');
    }
}
