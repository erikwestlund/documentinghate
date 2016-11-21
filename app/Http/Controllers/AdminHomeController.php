<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class AdminHomeController extends Controller
{
    /**
     * Show the admin dashboard.
     * 
     * @return View|redirect
     */
    protected function show()
    {
        if(Auth::user()->can('moderate-incidents')){
            return redirect('/admin/incidents');
        } else {
            return view('admin.home');
        }
    }
}
