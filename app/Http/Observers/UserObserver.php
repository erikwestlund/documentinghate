<?php

namespace App\Observers;

use Notification;

use App\Role;
use App\User;
use App\Notifications\UserRegistered;

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

        $this->notifyAdministratorsOfNewUser($user);
    }

    /**
     * When a new user registeres, notify the administrators.
     * @param  User $user 
     * @return Void
     */
    protected function notifyAdministratorsOfNewUser($user)
    {
        $users_to_notify = User::administrators()->
            get();

        Notification::send($users_to_notify, new UserRegistered($user));
    }

}