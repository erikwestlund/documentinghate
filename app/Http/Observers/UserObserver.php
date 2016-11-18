<?php

namespace App\Observers;

use App\Role;
use App\User;

class UserObserver
{
    /**
     * Listen to the User created event.

     * Attach user role.
     *
     * @param  User  $user
     * @return void
     */
    public function created(User $user)
    {
        if($user->id != 1) {
            $user->attachRole(Role::where('name', 'user')->first());
        }
    }

}