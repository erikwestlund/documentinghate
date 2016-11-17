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

    protected $incident_types = [
        'verbal_abuse',
        'harassment',
        'intimidation',
        'physical_violence',
        'vandalism',
        'property_crime',
        'other'
    ];

    public function getGoogleMapsUrlAttribute()
    {
        return 'https://www.google.com/maps/place/' . $this->google_maps_latitude . ',' . $this->google_maps_longitude;
    }

    public function getSourceProcessedAttribute()
    {
        if($this->source == 'other') {
            return $this->source_other_description;
        }

        return title_case(str_replace('_', ' ', $this->source));
    }

    public function getIncidentTypesAttribute()
    {
        $incident_types = collect($this->incident_types)->map(function($item) {
            return $this->{$item} ? title_case(str_replace('_', ' ', $item)) : null;
        })->toArray();

        return implode(', ', array_filter($incident_types));
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
