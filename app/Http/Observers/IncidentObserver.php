<?php

namespace App\Observers;

use App\Incident;

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
    public function saving(Incident $incident)
    {
        if(! $incident->slug) {
            $incident->slug = str_slug($incident->title);
        }
    }

}