<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{

    protected $guarded = [
        'id'
    ];

    protected $data = [
        'date'
    ];

    public $source_dictionary = [
        'news_article',
        'it_happened_to_me',
        'i_witnessed_it',
        'someone_i_know_witnessed_it',
        'social_media',
        'other',
    ];

    public $incident_type_dictionary = [
        'verbal_abuse',
        'harassment',
        'intimidation',
        'physical_violence',
        'vandalism',
        'property_crime',
        'other'
    ];

    /**
     * Get a link to the lcoadtion on google maps
     * @return String
     */
    public function getGoogleMapsUrlAttribute()
    {
        return 'https://www.google.com/maps/place/' . $this->google_maps_latitude . ',' . $this->google_maps_longitude;
    }

    /**
     * Get incident types in pretty form.
     * @return String 
     */
    public function getIncidentTypesAttribute()
    {
        $incident_types = collect($this->incident_type_dictionary)->map(function($item) {
            return $this->{$item} ? title_case(str_replace('_', ' ', $item)) : null;
        })->toArray();

        return implode(', ', array_filter($incident_types));
    }

    /**
     * Get location in pretty form.
     * @return String
     */
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

    /**
     * Get the maximum non-deleted ID number
     * @return Int
     */
    public function getMaxIdAttribute()
    {
        return Incident::where('id', DB::raw("(select max(`id`) from incidents)"))
                ->first()
                ->id;
    }

    /**
     * Get the minimum non-deleted ID number
     * @return Int
     */
    public function getMinIdAttribute()
    {
        return Incident::where('id', DB::raw("(select min(`id`) from incidents)"))
                ->first()
                ->id;
    }

    public function getNextIncidentUrlAttribute()
    {
        if($this->id == $this->max_id) {
            $id = 1;
        } else {
            $id = $this->id + 1;
        }

        return url('/admin/incidents/' . $id);
    }

    /**
     * Get the next unmoderated incident
     * @return String
     */
    public function getNextUnmoderatedIncidentUrlAttribute()
    {
        //select * from foo where id = (select min(id) from foo where id > 4)
        $next = Incident::where('id', DB::raw("(select min(`id`) from incidents where `id` > ' . $this->id . ' and `approved` is null)"))
                ->first();

        if(! $next) {
            $next = Incident::where('id', DB::raw("(select min(`id`) from incidents where `approved` is null)"))
                    ->first();
        }

        if($next) {
            return url('/admin/incidents/' . $next->id);
        }

        return false;
    }

    public function getPreviousIncidentUrlAttribute()
    {
        if($this->id == 1) {
            $id = $this->max_id;
        } else {
            $id = $this->id - 1;
        }

        return url('/admin/incidents/' . $id);
    }

    /**
     * Get the previous unmoderated incident
     * @return String
     */
    public function getPreviousUnmoderatedIncidentUrlAttribute()
    {
        //select * from foo where id = (select min(id) from foo where id > 4)
        $previous = Incident::where('id', DB::raw("(select max(`id`) from incidents where `id` < ' . $this->id . ' and `approved` is null)"))
                ->first();

        if(! $previous) {
            $previous = Incident::where('id', DB::raw("(select max(`id`) from incidents where `approved` is null)"))
                    ->first();
        }

        if($previous) {
            return url('/admin/incidents/' . $previous->id);
        }

        return false;
    }

    /**
     * Get the source in pretty form.
     * @return String
     */
    public function getSourceProcessedAttribute()
    {
        if($this->source == 'other') {
            return $this->source_other_description;
        }

        return title_case(str_replace('_', ' ', $this->source));
    }

    /**
     * Incidents have many moderation decisions.
     * @return Collection
     */
    public function moderation_decisions()
    {
        return $this->hasMany('App\IncidentModerationDecision');
    }

}
