<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;
use Illuminate\Http\Request;

class AdminUsersEditController extends Controller
{
    public function delete(User $user)
    {
        return view('admin.users-delete', compact('user'));
    }

    public function destroy(User $user)
    {
        $user->delete();

        flash()->success('User successfully deleted.');

        return redirect('/admin/users'); 
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users-edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id . '|max:255',
            'password' => 'sometimes|min:6|confirmed',
            'role' => 'required',
        ]);

        // update the user
        $user->name = $request->name;
        $user->email = $request->email;

        // only update changed passwords
        if($user->password) {
            $user->password = bcrypt($request->password);
        }

        // if a new role, change it
        $new_role = Role::where('name', $request->role)->first();

        if(! $user->hasRole($new_role->name)) {
            $user->syncRoles([$new_role->id]);
        }

        $user->save();

        flash()->success('User successfully updated.');

        return back();

    }
}
