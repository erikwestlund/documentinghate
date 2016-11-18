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
        $page = $request->page ?? 1;
        $search = $request->search ?? null;

        $incidents = Incident::approved()
            ->orderBy('date', 'desc')
            ->paginate($this->per_page);

        return view('home', compact('incidents', 'page', 'search'));
    }
}
