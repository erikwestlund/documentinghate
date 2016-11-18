<?php

namespace App\Http\Controllers;

use App\Incident;
use Illuminate\Http\Request;

class IncidentPageController extends IncidentController
{
    public function show(Request $request)
    {
        $incident = Incident::where('slug', $request->slug)
            ->first();
        
        if(!$incident) {
            abort(404);
        }

        return view('incident', compact('incident'));
    }
}
