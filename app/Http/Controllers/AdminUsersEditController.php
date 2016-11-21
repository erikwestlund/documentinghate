<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;
use Illuminate\Http\Request;

class AdminUsersEditController extends Controller
{
    /**
     * Check if user authorized to perform edit.
     * @param  User    $user           
     * @param  User    $logged_in_user 
     * @return boolean|abort
     */
    public function isAuthorizedToEdit(User $user, User $logged_in_user)
    {
        if($logged_in_user->can('edit-users') || $logged_in_user->id == $user->id) {
            return true;
        }

        abort(403);
    }

    /**
     * Show the form to delete a user.
     * 
     * @param  int  $id      the user id
     * @param  Request $request 
     * @return View
     */
    public function delete($id, Request $request)
    {   
        $user = User::find($id);
        return view('admin.users-delete', compact('user'));
    }

    /**
     * Delete a user.
     * 
     * @param  int  $id      the user id
     * @param  Request $request 
     * @return Redirect
     */
    public function destroy($id, Request $request)
    {   
        $user = User::find($id);

        $user->delete();

        flash()->success('User successfully deleted.');

        return redirect('/admin/users'); 
    }

    /**
     * Show the form to edit a user.
     * @param  int  $id      the user id
     * @param  Request $request 
     * @return View
     */
    public function edit($id, Request $request)
    {   
        $user = User::find($id);
        $this->isAuthorizedToEdit($user, $request->user());

        $roles = Role::all();

        return view('admin.users-edit', compact('user', 'roles'));
    }

    /**
     * Update a user.
     * 
     * @param  int  $id      the uesr id
     * @param  Request $request 
     * @return Redirect
     */
    public function update($id, Request $request)
    {   
        $user = User::find($id);

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id . '|max:255',
            'password' => 'sometimes|min:6|confirmed',
        ]);

        // update the user
        $user->name = $request->name;
        $user->email = $request->email;

        // only update changed passwords
        if($request->password) {
            $user->password = bcrypt($request->password);
        }

        // if a new role, change it
        if($request->user()->can('edit-users') && $request->role) {
            $new_role = Role::where('name', $request->role)->first();

            if(! $user->hasRole($new_role->name)) {
                $user->syncRoles([$new_role->id]);
            }            
        }

        $user->moderation_notification_frequency = $request->moderation_notification_frequency;

        $user->save();

        flash()->success('User successfully updated.');

        return back();

    }
}
