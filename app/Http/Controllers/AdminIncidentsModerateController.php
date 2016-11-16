<?php

namespace App\Http\Controllers;

use App\Incident;
use Illuminate\Http\Request;

class AdminIncidentsModerateController extends Controller
{
    public function edit($id)
    {
        $incident = Incident::find($id);

        return view('admin.incidents-moderate', compact('incident'));
    }
}
