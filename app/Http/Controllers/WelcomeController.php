<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('redirectDashboard');
    }
    public function welcome(Request $request)
    {
        if (auth()->check()) {
            if (count(auth()->user()->roles) > 0) {
                return redirect()->route('admin.dashboard');
            }elseif(auth()->user()->employee){
                return redirect()->route('employee.dashboard');
            }elseif(auth()->user()->customer){
                return redirect()->route('customer.dashboard');
            }
            else{
                return redirect()->route('user.dashboard');
            }
        }else
        {
            return view('front.welcome');
        }

    }
}
