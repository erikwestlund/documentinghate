<?php

namespace App;

use Laracasts\Matryoshka\Cacheable;

use Illuminate\Database\Eloquent\Model;

class DeletedIncidentPhoto extends Model
{
    use Cacheable;
    
    protected $touches = ['incident'];    

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function incident()
    {
        return $this->belongsTo('App\Incident');
    }
}
