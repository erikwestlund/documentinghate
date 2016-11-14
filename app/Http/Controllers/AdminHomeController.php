<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminHomeController extends Controller
{
    protected function show()
    {
        return view('admin.home');
    }
}
