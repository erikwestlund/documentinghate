<?php

namespace App\Http\Controllers;

use App\Incident;
use Illuminate\Http\Request;

class IncidentPageController extends IncidentController
{
    public function show($id, $slug, Request $request)
    {
        $incident = Incident::approved()
            ->where('id', $id)
            ->where('slug', $request->slug)
            ->first();
        
        if(!$incident) {
            abort(404);
        }

        return view('incident', compact('incident'));
    }
}
