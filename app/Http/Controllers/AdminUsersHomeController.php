<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class AdminUsersHomeController extends Controller
{
    /**
     * Shoe the users admin page.
     * 
     * @return View
     */
    public function show()
    {
        $users = User::paginate(config('site.admin.per_page'));
        return view('admin.users', compact('users'));
    }
}
