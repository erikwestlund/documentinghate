<?php

namespace App\Http\Controllers;

use App\Incident;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $per_page;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->per_page = config('site.per_page');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $search = $request['query'];

        if($search) {
            $incidents = Incident::search($search)
                ->where('approved', 1);
        } else {
            $incidents = Incident::approved()
                ->orderBy('date', 'desc');
        }

        $incidents = $incidents->paginate($this->per_page);
        $geo_data = $this->getAllGeoData($search);

        return view('home', compact('incidents', 'geo_data', 'search'));
    }

    protected function getAllGeoData()
    {
        return Incident::approved()
            ->select('id', 'date', 'title', 'slug', 'location_name', 'city', 'state', 'latitude', 'longitude')
            ->get();
    }
}
