<?php

namespace App\Http\Controllers;

use Image;
use Storage;
use Validator;
use App\Incident;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    protected $disk;
    protected $photos_directory;
    protected $success_message = 'Thank you for your submission. We will review it as quickly as possible.';

    protected $moderate_rules = [
        'approved' => 'required',
        'moderation_comment' => 'required_if:approved,0',
    ];

    protected $moderate_messages = [
        'approved.required' => 'Please choose whether or not to approve this incident.',
        'moderation_comment.required_if' => 'Please enter a reason for rejecting this incident.'
    ];

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
        $file_name = $incident->id . '-' . time() . '.' . $extension;

        $processed_photo = Image::make($photo);
        $file_content = $processed_photo->stream($extension, 100)->getContents();

        $path = $this->photos_directory . $file_name;

        $this->disk->put($path, $file_content);

        $url = $this->disk->url($path);

        $incident->photo_url = $url;
        $incident->save();
        return $url;
    }

    protected function getValidator(Request $request, $with_moderation = false)
    {
        $rules = [
            'title' => 'required',
            'date' => 'required|date',
            'source' => 'required',
            'city' => 'required',
            'state' => 'required',
            'description' => 'required',
            'news_article_url' => 'required_if:source,news|url',
            'social_medial_url' => 'required_if:source,social_media|url',
            'submitter_email' => 'required_if:source,witness|required_if:source,someone_else_witnessed|required_if:source,other|email',
            'source_other_description' => 'required_if:source,other',
            'other_incident_description' => 'required_if:other,true',
            'photo' => 'sometimes|image',
        ];

        $messages = [
            'title.required' => 'Please provide a one line incident description.',
            'date.required' => 'Please indicate when this incident took place.',
            'source.required' => 'Please indicate how you know about this incident.',
            'city.required' => 'Please indicate  in which city this took place.',
            'state.required' => 'Please indicate in which state this took place.',
            'description.required' => 'Please provide a description of the incident.',
            'news_article_url.required_if' => 'Please provide a link to the news source.',
            'news_article_url.url' => 'The provided news article link is invalid.',
            'social_medial_url.required_if' => 'Please provide a link to the social media post.',
            'social_medial_url.url' => 'The provided social media post URL is invalid.',
            'submitter_email.required_if' => 'Please provide a contact e-mail address.',
            'submitter_email.email' => 'The provided email e-mail address is invalid.',
            'source_other_description.required_if' => 'Please describe how you learned about this incident.',
            'other_incident_description.required_if' => 'Please describe how you would classify this incident.',
        ];

        if($with_moderation) {
            $rules = array_merge($this->moderate_rules, $rules);
            $messages = array_merge($this->moderate_messages, $messages);
        }

        return  Validator::make($request->all(), $rules, $messages);
    }
}
