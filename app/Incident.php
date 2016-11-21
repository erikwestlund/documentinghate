<?php

namespace App;

use DB;
use Laravel\Scout\Searchable;
use Laracasts\Matryoshka\Cacheable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{

    use Cacheable;
    use Notifiable;
    use Searchable;
    
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
     * Incidents have many moderation decisions.
     * 
     * @return Collection
     */
    public function deleted_photos()
    {
        return $this->hasMany('App\DeletedIncidentPhoto');
    }

    public function getDescriptionHtmlAttribute()
    {
        return nl2br($this->description);
    }

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
     * Get the incident view url
     * @return string
     */
    public function getUrlAttribute()
    {
        return url('/incidents/' . $this->slug);
    }

    /**
     * Get the url to moderate the incident
     * @return string
     */
    public function getAdminUrlAttribute()
    {
        return url('/admin/incidents/' . $this->id);
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
    public function getNextIncidentAdminUrlAttribute()
    {
        return $this->next_id ? url('/admin/incidents/' . $this->next_id) : null;
    }

    /**
     * Get the previous incident URL
     * 
     * @return String 
     */
    public function getNextIncidentUrlAttribute()
    {
        $slug = Incident::find($this->next_id)->slug;
        return $this->prev_id ? url('/incidents/' . $slug) : null;
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
    public function getNextUnmoderatedIncidentAdminUrlAttribute()
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
    public function getPreviousIncidentAdminUrlAttribute()
    {
        return $this->prev_id ? url('/admin/incidents/' . $this->prev_id) : null;
    }

    /**
     * Get the previous incident URL
     * 
     * @return String 
     */
    public function getPreviousIncidentUrlAttribute()
    {
        $slug = Incident::find($this->prev_id)->slug;
        return $this->prev_id ? url('/incidents/' . $slug) : null;
    }

    /**
     * Get the previous unmoderated incident
     * 
     * @return String
     */
    public function getPreviousUnmoderatedIncidentAdminUrlAttribute()
    {
        return $this->prev_unmoderated_id ? url('/admin/incidents/' . $this->prev_unmoderated_id) : null;
    }

    public function getShortDescriptionHtmlAttribute()
    {
        $html = nl2br(str_limit($this->description, config('site.short_description_length')));

        if(strlen($this->description) >= config('site.short_description_length')) {
            $html .= ' <a href="' . $this->url . '">Read More</a>';
        }

        return $html;
    }

    /**
     * Get HTML to display the Source
     * 
     * @return string
     */
    public function getSourceHtmlAttribute()
    {
        if($this->source == 'news_article') {
            return '<i class="fa fa-newspaper-o"></i> <a href="' . $this->news_article_url . '">News Article</a>';
        }

        if($this->source == 'i_witnessed_it' || $this->source == 'someone_i_know_witnessed_it') {
            return '<i class="fa fa-eye"></i> Eye witness';
        }

        if($this->source == 'social_media') {

            if(str_contains($this->social_media_url, 'facebook.com')) {
                return '<i class="fa fa-facebook-square"></i> <a href="' . $this->social_media_url . '">Facebook</a>';
            } else if(str_contains($this->social_media_url, 'twitter.com')) {
                return '<i class="fa fa-twitter-square"></i> <a href="' . $this->social_media_url . '">Twitter</a>';
            } else {
                return '<i class="fa fa-link"></i> <a href="' . $this->social_media_url . '">Link</a>';
            }

        }

        return null;
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

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = [];

        // Customize array...
        $array['id'] = $this->id;
        $array['title'] = $this->title;
        $array['description'] = $this->description;
        $array['city'] = $this->city;
        $array['state'] = config('constants.us_states')[$this->state];
        $array['state_abbrev'] = $this->state;
        $array['approved'] = $this->approved;

        return $array;
    }

}
