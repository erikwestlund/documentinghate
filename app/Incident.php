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

    protected $source_dictionary = [
        'news' => 'News Article',
        'happened_to_me' => 'It Happened to Me',
        'witness' => 'I Witnessed It',
        'someone_else_witnessed' => 'Someone Else Witnessed It',
        'social_media' => 'Social Media',
        'other' => 'Other'
    ];

    public function getGoogleMapsUrlAttribute()
    {
        return 'https://www.google.com/maps/place' . $this->google_maps_latitude . ',' . $this->google_maps_longitude;
    }

    public function getSourceProcessedAttribute()
    {
        if($this->source == 'other') {
            return $this->source_other_description;
        }

        return $this->source_dictionary[$this->source];
    }

    public function getLocationAttribute()
    {
        $elements = [];

        if($this->location_name) {
            $elements[] = $this->location_name;
        }

        $elements[] = $this->city;
        $elements[] = $this->state;

        return implode(', ', $elements);
    }
}
