<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeletedIncidentPhoto extends Model
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
