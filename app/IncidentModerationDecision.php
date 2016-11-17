<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IncidentModerationDecision extends Model
{

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function incident()
    {
        return $this->belongsTo('App\Incident');
    }
}
