<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $guarded = [
        'id'
    ];

    protected $data = [
        'date'
    ];
}
