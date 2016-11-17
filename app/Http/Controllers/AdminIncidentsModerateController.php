<?php

namespace App\Http\Controllers;

use Auth;
use App\Incident;
use App\IncidentModerationDecision;
use Illuminate\Http\Request;

class AdminIncidentsModerateController extends IncidentController
{
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
     * Approve the incdient
     * @param  Request $request 
     * @param  Int  $id      
     * @return Redirect
     */
    public function approve(Request $request, $id)
    {
        $this->validate($request, [
            'approved' => 'required',
            'moderation_comment' => 'required_if:approved,0',
        ], [
            'approved.required' => 'Please choose whether or not to approve this incident.',
            'moderation_comment.required_if' => 'Please enter a reason for rejecting this incident.'
        ]);

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

    public function update(Request $request)
    {
       $validator = $this->getValidator($request);

       if(!$validator->passes()) {
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
}
