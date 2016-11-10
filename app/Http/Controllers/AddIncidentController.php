<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AddIncidentController extends Controller
{
    public function create()
    {
        return view('incidents.add');
    }
}
