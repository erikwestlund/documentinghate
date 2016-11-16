<?php

namespace App\Http\Controllers;

use DB;
use App\Incident;
use App\EloquentVueTables;
use Illuminate\Http\Request;

class AdminIncidentsHomeController extends Controller
{
    public function show()
    {
        $incidents = Incident::
            orderBy('approved', 'asc')
            ->orderBy('date', 'desc')
            ->paginate(config('site.admin.per_page'));

        return view('admin.incidents', compact('incidents'));
    }

    public function getIncidents(Request $request) {
        $data = new EloquentVueTables($request);
        return $data->get(new Incident, [
            'id',
            'date',
            'title',
            'city',
            'state',
            'approved'
        ]);
    }
}
