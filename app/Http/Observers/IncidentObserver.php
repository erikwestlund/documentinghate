<?php

namespace App\Observers;

use Notification;

use App\User;
use App\Incident;
use App\Notifications\IncidentSubmitted;

class IncidentObserver
{
    /**
     * Listen to the Incident created event.
     *
     * Generate a slug
     * 
     * @param  Incident  $incident
     * @return void
     */
    public function created(Incident $incident)
    {
        $this->notifySubscribedModerators($incident);
    }

    /**
     * Listen to the Incident saving event.
     *
     * Generate a slug
     * 
     * @param  Incident  $incident
     * @return void
     */
    public function saving(Incident $incident)
    {
        if(! $incident->slug) {
            $incident->slug = str_slug($incident->title);
        }
    }

    /**
     * Notify moderators subscribed to per-incident notification.
     * 
     * @param  Incident $incident 
     * @return Void
     */
    protected function notifySubscribedModerators(Incident $incident)
    {
        $users_to_notify = User::moderators()
            ->receivesNotificationEverySubmission()
            ->get();

        Notification::send($users_to_notify, new IncidentSubmitted($incident));
    }

}