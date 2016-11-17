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
        'harassment',
        'intimidation',
        'physical_violence',
        'property_crime',
        'vandalism',
        'verbal_abuse',        
        'other'
    ];

    public $sources_where_submitter_email_required = [
        'it_happened_to_me',
        'i_witnessed_it',
        'someone_i_know_witnessed_it',
        'other'
    ];

    /**
     * Get a link to the lcoadtion on google maps
     * 
     * @return String
     */
    public function getGoogleMapsUrlAttribute()
    {
        return 'https://www.google.com/maps/place/' . $this->google_maps_latitude . ',' . $this->google_maps_longitude;
    }

    /**
     * Get incident types in pretty form.
     * 
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
     * 
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
     * Get the maximum approved incident ID
     * 
     * @return Int
     */
    public function getMaxApprovedIdAttribute()
    {
        return Incident::approved()
                ->select(DB::raw("max(`id`) as id"))
                ->first();
    }

    /**
     * Get the maximum incident ID
     * 
     * @return Int
     */
    public function getMaxIdAttribute()
    {
        return Incident::select(DB::raw("max(`id`) as id"))
                ->first();
    }

    /**
     * Get the minimum approved incident ID
     * 
     * @return Int
     */
    public function getMinApprovedIdAttribute()
    {
        return Incident::approved()
                ->select(DB::raw("min(`id`) as id"))
                ->first();
    }

    /**
     * Get the minimum ID number
     * 
     * @return Int
     */
    public function getMinIdAttribute()
    {
        return Incident::select(DB::raw("min(`id`) as id"))
                ->first();
    }

    /**
     * Get the next unmoderated ID
     * 
     * @return Int
     */
    public function getNextApprovedIdAttribute()
    {
        $next = Incident::approved()
                ->select(DB::raw("min(`id`) as id"))
                ->where('id', '>', $this->id)
                ->first();

        if($next->id) {
            return $next->id;
        }

        $next = Incident::approved()
            ->select(DB::raw("min(`id`) as id"))
            ->first();

        if($next->id) {
            return $next->id;
        }

        return null;
    }

    /**
     * Get the next incident ID
     * 
     * @return Int
     */
    public function getNextIdAttribute()
    {
        $next = Incident::select(DB::raw("min(`id`) as id"))
                ->where('id', '>', $this->id)
                ->first();

        if($next->id) {
            return $next->id;
        }

        $next = Incident::select(DB::raw("min(`id`) as id"))
            ->first();

        if($next->id) {
            return $next->id;
        }

        return null;
    }

    /**
     * Get next indcident URL.
     * 
     * @return String
     */
    public function getNextIncidentUrlAttribute()
    {
        return $this->next_id ? url('/admin/incidents/' . $this->next_id) : null;
    }

    /**
     * Get the next unmoderated ID
     * 
     * @return Int
     */
    public function getNextUnmoderatedIdAttribute()
    {
        $next = Incident::unmoderated()
                ->select(DB::raw("min(`id`) as id"))
                ->where('id', '>', $this->id)
                ->first();

        if($next->id) {
            return $next->id;
        }

        $next = Incident::unmoderated()
            ->select(DB::raw("min(`id`) as id"))
            ->first();

        if($next->id) {
            return $next->id;
        }

        return null;
    }

    /**
     * Get the next unmoderated incident
     * 
     * @return String
     */
    public function getNextUnmoderatedIncidentUrlAttribute()
    {
        return $this->next_unmoderated_id ? url('/admin/incidents/' . $this->next_unmoderated_id) : null;
    }

    public function getPrevApprovedIdAttribute()
    {
        $prev = Incident::approved()
                ->select(DB::raw("max(`id`) as id"))
                ->where('id', '<', $this->id)
                ->first();

        if($prev->id) {
            return $prev->id;
        }

        $prev = Incident::approved()
            ->select(DB::raw("max(`id`) as id"))
            ->first();

        if($prev->id) {
            return $prev->id;
        }

        return null;
    }

    /**
     * Get the previous incident ID
     * 
     * @return Int
     */
    public function getPrevIdAttribute()
    {
        $prev = Incident::select(DB::raw("max(`id`) as id"))
                ->where('id', '<', $this->id)
                ->first();

        if($prev->id) {
            return $prev->id;
        }

        $prev = Incident::select(DB::raw("max(`id`) as id"))
            ->first();

        if($prev->id) {
            return $prev->id;
        }

        return null;
    }

    /**
     * Get the previous unmoderated incident ID
     * 
     * @return Int
     */
    public function getPrevUnmoderatedIdAttribute()
    {
        $prev = Incident::unmoderated()
                ->select(DB::raw("max(`id`) as id"))
                ->where('id', '<', $this->id)
                ->first();

        if($prev->id) {
            return $prev->id;
        }

        $prev = Incident::unmoderated()
            ->select(DB::raw("max(`id`) as id"))
            ->first();

        if($prev->id) {
            return $prev->id;
        }

        return null;
    }

    /**
     * Get the previous incident URL
     * 
     * @return String 
     */
    public function getPreviousIncidentUrlAttribute()
    {
        return $this->prev_id ? url('/admin/incidents/' . $this->prev_id) : null;
    }

    /**
     * Get the previous unmoderated incident
     * 
     * @return String
     */
    public function getPreviousUnmoderatedIncidentUrlAttribute()
    {
        return $this->prev_unmoderated_id ? url('/admin/incidents/' . $this->prev_unmoderated_id) : null;
    }

    /**
     * Get the source in pretty form.
     * 
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
     * 
     * @return Collection
     */
    public function moderation_decisions()
    {
        return $this->hasMany('App\IncidentModerationDecision');
    }

    /**
     * Incidents have many moderation decisions.
     * 
     * @return Collection
     */
    public function deleted_photos()
    {
        return $this->hasMany('App\DeletedIncidentPhoto');
    }

    /**
     * Scope a query to only include approved cases.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

    /**
     * Scope a query to only include unmoderated cases.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeModerated($query)
    {
        return $query->whereNotNull('approved');
    }

    /**
     * Scope a query to only include unapproved cases.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnapproved($query)
    {
        return $query->where('approved', false);
    }

    /**
     * Scope a query to only include unmoderated cases.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnmoderated($query)
    {
        return $query->whereNull('approved');
    }

}
