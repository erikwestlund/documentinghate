<?php

namespace App\Http\Controllers;

use Image;
use Storage;
use Validator;
use App\Incident;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Jobs\GeocodeIncidentLocation;
use App\Jobs\MakeIncidentPhotoThumbnail;

class IncidentAddController extends Controller
{
    protected $disk;
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
     * Show the submission form.
     * 
     * @param  Request $request 
     * @return View
     */
    public function create(Request $request)
    {
        return view('incidents.add');
    }

    /**
     * Store an incident from a submission.
     * 
     * @param  Request $request 
     * @return Array
     */
    public function store(Request $request)
    {
        // validate the data
        $validator = $this->validateIncident($request);

        if($validator['status'] == 'failure') {
            return $validator;
        }

        // store the record
        $incident = $this->createIncident($request);
        
        // check if there's an photo. save it.
        $photo = $request->file('photo');
        if($photo) {
            $this->addUploadedPhoto($photo, $incident);
        }        

        // dispatch a job to make a thumbnail
        dispatch(new MakeIncidentPhotoThumbnail($incident));
        dispatch(new GeocodeIncidentLocation($incident));
        
        // geocode the cite and state
        // email the moderators

        $status = 'success';
        $message = $this->success_message;
        return compact('status', 'message');
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

    /**
     * Save the incident.
     * @param  Request $request 
     * @return Incident
     */
    protected function createIncident(Request $request)
    {
        $incident = new Incident;

        $incident->title = $request->title;
        $incident->date = Carbon::parse($request->date);
        $incident->city = $request->city;
        $incident->state = $request->state;
        $incident->location_name = $request->location_name;
        
        $incident->source = $request->source;
        $incident->harassment = $request->harassment == 'true' ? true : false;
        $incident->intimidation = $request->intimidation == 'true' ? true : false;
        $incident->physical_violence = $request->physical_violence == 'true' ? true : false;
        $incident->property_crime = $request->property_crime == 'true' ? true : false;
        $incident->other = $request->other == 'true' ? true : false;
        $incident->other_incident_description = $request->other ? $request->other_incident_description : null;

        $incident->source_other_description = $request->source == 'other' ? $request->source_other_description : null;
        $incident->news_article_url = $request->source == 'news' ? $request->news_article_url : null;
        $incident->social_media_url = $request->source == 'social_media' ? $request->social_media_url : null;
        $incident->submitter_email = in_array($request->source, ['witness', 'someone_else_witnessed', 'other']) ? $request->submitter_email : null;
        
        $incident->vandalism = $request->vandalism == 'true' ? true : false;
        $incident->verbal_abuse = $request->verbal_abuse == 'true' ? true : false;
        $incident->description = $request->description;
        $incident->ip = $request->ip();
        $incident->user_agent = $request->header('User-Agent');

        $incident->save();

        return $incident;
    }

    /**
     * Validate the submission.
     *
     * @param  Request $request
     * @return Array
     */
    protected function validateIncident(Request $request)
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

        $validator =  Validator::make($request->all(), $rules, $messages);

        if ($validator->passes()) {
            return [
                'status' => 'success'
            ];
        }

        return [
            'status' => 'failure',
            'errors' => $validator->errors()
        ];
    }

}
