<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Incident;
use Carbon\Carbon;
use App\Jobs\GeocodeIncidentLocation;
use App\Jobs\MakeIncidentPhotoThumbnail;

class IncidentAddController extends IncidentController
{
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
        $validator = $this->getValidator($request);

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
