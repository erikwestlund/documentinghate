<?php

namespace App\Http\Controllers;

use Storage;
use Validator;
use App\Incident;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Jobs\MakeIncidentImageThumbnail;

class AddIncidentController extends Controller
{

    protected $success_message = 'Thank you for your submission. We will review it as quickly as possible.';

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
        
        // check if there's an image. save it.
        $image = $request->file('image');
        if($image) {
            $this->addUploadedImage($image, $incident);
        }        

        // dispatch a job to make a thumbnail
        dispatch(new MakeIncidentImageThumbnail($incident));
        
        // geocode the cite and state
        // email the moderators

        $status = 'success';
        $message = $this->success_message;
        return compact('status', 'message');
    } 

    /**
     * Store the uploaded image and save the URL to the incident.
     * 
     * @param UploadedFile   $image    
     * @param Incident $incident 
     */
    protected function addUploadedImage($image, Incident $incident)
    {
        $extension = $image->guessClientExtension();
        $file_name = $incident->id . '-' . time() . '.' . $extension;

        $path = $image->storeAs('photos', $file_name,'s3');
        $url = Storage::disk('s3')->url($path);

        $incident->image_url = $url;
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
        $incident->how_known = $request->how_known;
        $incident->harassment = $request->harassment == 'true' ? true : false;
        $incident->intimidation = $request->intimidation == 'true' ? true : false;
        $incident->physical_violence = $request->physical_violence == 'true' ? true : false;
        $incident->property_crime = $request->property_crime == 'true' ? true : false;
        $incident->submitter_email = $request->submitter_email;
        $incident->vandalism = $request->vandalism == 'true' ? true : false;
        $incident->verbal_abuse = $request->verbal_abuse == 'true' ? true : false;
        $incident->other_incident_type = $request->other_incident_type;
        $incident->description = $request->description;
        $incident->ip = $request->ip();
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
            'date' => 'required',
            'how_known' => 'required',
            'city' => 'required',
            'state' => 'required',
            'description' => 'required',
            'submitter_email' => 'sometimes|email'

        ];

        $messages = [
            'title.required' => 'Please provide a one line incident description.',
            'date.required' => 'Please indicate when this incident took place.',
            'how_known.required' => 'Please indicate how you know about this incident.',
            'city.required' => 'Please indicate  in which city this took place.',
            'state.required' => 'Please indicate in which state this took place.',
            'description.required' => 'Please provide a description of the incident.',
            'submitter_email.email' => 'Please provide a valid email address.'
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
