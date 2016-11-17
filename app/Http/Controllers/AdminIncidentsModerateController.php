<?php

namespace App\Http\Controllers;

use Auth;
use App\Incident;
use Illuminate\Http\Request;

class AdminIncidentsModerateController extends Controller
{
    public function edit($id)
    {
        $incident = Incident::find($id);

        if(Auth::user()->can('edit-incidents')) {
            return view('admin.incidents-moderate', compact('incident'));
        } else {
            return view('admin.incidents-moderate', compact('incident'));
        }
    
        
    }

    public function approve(Request $request, $id)
    {
        $incident = Incident::find($id);

        if($request->approve == 1) {
            $incident->approved = 1;
        }

        if($request->approve == 0) {
            $incident->approved = 0;
        }

        flash()->success('test');
        return redirect('/admin/incidents');
    }
}
