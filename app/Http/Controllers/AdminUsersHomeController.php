<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class AdminUsersHomeController extends Controller
{
    public function show()
    {
        $users = User::paginate(15);
        return view('admin.users', compact('users'));
    }
}
