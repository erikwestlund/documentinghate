<?php

namespace App\Http\Controllers;

use Auth;
use Image;
use Storage;
use Validator;

use Carbon\Carbon;

use App\Incident;
use App\DeletedIncidentPhoto;
use App\Jobs\GeocodeIncidentLocation;
use App\Jobs\MakeIncidentPhotoThumbnail;

use Illuminate\Http\Request;

class IncidentController extends Controller
{

    protected $disk;

    protected $moderation_rules_first = [
        'approved' => 'required',
        'moderation_comment' => 'required_if:approved,0',
    ];

    protected $moderation_messages_first = [
        'approved.required' => 'Please choose whether or not to approve this incident.',
        'moderation_comment.required_if' => 'Please enter a reason for rejecting this incident.'
    ];

    protected $moderation_rules_when_revisited = [
        'approved' => 'required',
        'moderation_comment' => 'required',
    ];

    protected $moderation_messages_when_revisited = [
        'approved.required' => 'Please choose whether or not to approve this incident.',
        'moderation_comment.required' => 'Please enter a reason for changing your moderation decision for this incident.'
    ];

    protected $photos_directory;
    protected $success_message = 'Thank you for your submission. We will review it as quickly as possible.';

    /**
     * Construct the obejct.
     *
     * @return void
     */
    public function __construct()
    {
        $this->photos_directory = config('site.photos.directory');
        $this->disk = Storage::disk('s3');        
    }

    /**
     * Store the uploaded photo and save the URL to the incident.
     * 
     * @param UploadedFile   $photo    
     * @param Incident $incident 
     */
    protected function addUploadedPhoto($photo, Incident $incident)
    {
        $extension = $photo->guessClientExtension();
        $file_name = $incident->id . '-' . $incident->updated_at->timestamp . '.' . $extension;

        $processed_photo = Image::make($photo);
        $file_content = $processed_photo->stream($extension, 100)->getContents();

        $path = $this->photos_directory . $file_name;

        $this->disk->put($path, $file_content);

        $url = $this->disk->url($path);

        $incident->photo_url = $url;
        $incident->save();

        dispatch(new MakeIncidentPhotoThumbnail($incident));
    }

    /**
     * Delete a photo from an incident.
     * @param  Incident $incident 
     * @return Void
     */
    protected function deletePhoto(Incident $incident)
    {
        $deleted_incident_photo = new DeletedIncidentPhoto;

        $deleted_incident_photo->user_id = Auth::user()->id;
        $deleted_incident_photo->incident_id = $incident->id;
        $deleted_incident_photo->photo_url = $incident->photo_url;
        $deleted_incident_photo->thumbnail_photo_url = $incident->thumbnail_photo_url;

        $deleted_incident_photo->save();

        $incident->photo_url = '';
        $incident->thumbnail_photo_url = '';
        $incident->save();
    }

    /**
     * Get validation rules for incidents that require emails.
     * 
     * @return string
     */
    protected function getSubmitterEmailValidationRules()
    {
        $submitter_email_rules = [];

        $sources_where_required =  (new Incident)->sources_where_submitter_email_required;

        foreach($sources_where_required as $source) {
            $submitter_email_rules[] = 'required_if:source,' . $source;            
        }

        $submitter_email_rules[] = 'email';

        return implode('|', $submitter_email_rules);
    }

    /**
     * Get a validator for an incident.
     * 
     * @param  Request $request               
     * @param  boolean $moderation_validation 
     * @return Validator
     */
    protected function getValidator(Request $request, $moderation_validation = false)
    {
        $rules = [
            'title' => 'required',
            'date' => 'required|date|before:' . Carbon::tomorrow(),
            'source' => 'required',
            'city' => 'required',
            'state' => 'required',
            'description' => 'required',
            'news_article_url' => 'required_if:source,news_article|url',
            'submitter_email' => 'required|email',
            'social_media_url' => 'required_if:source,social_media|url',
            'source_other_description' => 'required_if:source,other',
            'other_incident_description' => 'required_if:other,true',
            'photo' => 'sometimes|image',
        ];

        $messages = [
            'title.required' => 'Please provide a one line incident description.',
            'date.required' => 'Please indicate when this incident took place.',
            'date.before' => 'Please select a date before today.',
            'source.required' => 'Please indicate how you know about this incident.',
            'city.required' => 'Please indicate  in which city this took place.',
            'state.required' => 'Please indicate in which state this took place.',
            'description.required' => 'Please provide a description of the incident.',
            'news_article_url.required_if' => 'Please provide a link to the news source.',
            'news_article_url.url' => 'The provided news article link is invalid.',
            'social_media_url.required_if' => 'Please provide a link to the social media post.',
            'social_media_url.url' => 'The provided social media post URL is invalid.',
            'submitter_email.required' => 'Please provide your e-mail address.',
            'submitter_email.email' => 'The provided email e-mail address is invalid.',
            'source_other_description.required_if' => 'Please describe how you learned about this incident.',
            'other_incident_description.required_if' => 'Please describe how you would classify this incident.',
        ];

        if($moderation_validation == 'first') {
            $rules = array_merge($this->moderation_rules_first, $rules);
            $messages = array_merge($this->moderation_messages_first, $messages);
        } else  if($moderation_validation == 'revisit') {
            $rules = array_merge($this->moderation_rules_when_revisited, $rules);
            $messages = array_merge($this->moderation_messages_when_revisited, $messages);
        }

        return  Validator::make($request->all(), $rules, $messages);
    }

    /**
     * Save changes to the incident.
     * 
     * @param  Request  $request  
     * @param  Incident $incident 
     * @return Void
     */
    protected function saveInput(Request $request, Incident $incident)
    {
        $incident->title = $request->title;

        if($request->slug) {
            $incident->slug = $request->slug;
        }

        $incident->date = Carbon::parse($request->date);
        $incident->city = $request->city;
        $incident->state = $request->state;
        $incident->location_name = $request->location_name;
        
        $incident->source = $request->source;

        foreach($incident->incident_type_dictionary as $incident_type) {
            $incident->{$incident_type} = $request->{$incident_type} == 'true' ? true : false;
        }

        $incident->other_incident_description = $request->other == 'true' ? $request->other_incident_description : '';

        $incident->source_other_description = $request->source == 'other' ? $request->source_other_description : null;
        $incident->news_article_url = $request->source == 'news_article' ? $request->news_article_url : null;
        $incident->social_media_url = $request->source == 'social_media' ? $request->social_media_url : null;
        
        $incident->vandalism = $request->vandalism == 'true' ? true : false;
        $incident->verbal_abuse = $request->verbal_abuse == 'true' ? true : false;

        if($request->remove_photo == 'true') {
            $this->deletePhoto($incident);
        }

        $incident->description = $request->description;

        $incident->submitter_email = in_array($request->source, $incident->sources_where_submitter_email_required) ? $request->submitter_email : null;
        $incident->email_when_approved = $request->email_when_approved == 'true' ? true : false;

        if($request->approval_email_sent) {
            $incident->approval_email_sent = $request->approval_email_sent;
        }

        $incident->ip = $request->ip ? $request->ip : $request->ip();
        $incident->user_agent = $request->user_agent ? $request->user_agent : $request->header('User-Agent');

        $incident->save();

        dispatch(new GeocodeIncidentLocation($incident));

        $photo = $request->file('photo');
        if($photo) {
            $this->addUploadedPhoto($photo, $incident);
        }      
    }

}
