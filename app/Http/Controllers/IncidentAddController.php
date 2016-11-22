<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Incident;

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

        $status = 'success';
        $message = $this->success_message . ' <a href="' . secure_url('/') . '">Go Back</a>.';
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

        $this->saveInput($request, $incident);

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
