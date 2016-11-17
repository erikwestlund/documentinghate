<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use App\Incident;
use App\IncidentModerationDecision;

use Illuminate\Http\Request;

class AdminIncidentsModerateController extends IncidentController
{
    /**
     * Approve the incdient
     * @param  Request $request 
     * @param  Int  $id      
     * @return Response
     */
    public function approve(Request $request, $id)
    {
        $validator = $this->validateApproval($request);

        if($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
       }

        $incident = Incident::find($id);

        $incident->approved = $request->approved;
        $incident->save();

        $this->logModerationDecision($request, $incident);

        if($incident->approved) {
            flash()->success('<strong>' . $incident->title . '</strong> has been approved.');
        } else {
            flash()->warning('<strong>' . $incident->title . '</strong> has been rejected.');
        }
        
        return back();
    }

    /**
     * Show the appropriate form given user's permissions.
     * @param  Int $id 
     * @return View
     */
    public function moderate($id)
    {
        $incident = Incident::with('moderation_decisions', 'moderation_decisions.user')
            ->find($id);

        if(Auth::user()->can('edit-incidents')) {
            return view('admin.incidents-moderate-edit', compact('incident'));
        } else {
            return view('admin.incidents-moderate', compact('incident'));
        }
    }

    /**
     * Update the record.
     * @param  Request $request 
     * @return Response
     */
    public function update(Request $request)
    {
       $validator = $this->getValidator($request, true);

       if($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
       }

        flash()->success('Incident has been successfully updated.');

        return back();
    }

    /**
     * Log the moderation decision
     * @param  Request  $request  
     * @param  Incident $incident 
     * @return Void
     */
    protected function logModerationDecision(Request $request, Incident $incident)
    {
        $decision = new IncidentModerationDecision;
        $decision->incident_id = $incident->id;
        $decision->user_id = Auth::user()->id;
        $decision->approved = $request->approved;
        $decision->comment = $request->moderation_comment;
        $decision->save();
    }

    /**
     * Validated the moderation.
     * 
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    protected function validateApproval(Request $request)
    {
        return Validator::make($request->all(),
                $this->moderate_rules,
                $this->moderate_messages);    
    }

}
