<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use Auth;
use Validator;
use App\Incident;
use App\IncidentModerationDecision;
use App\Notifications\IncidentSubmissionApproved;

use Illuminate\Http\Request;

class AdminIncidentsModerateController extends IncidentController
{
    /**
     * Approve the incident.
     * 
     * @param  Request $request 
     * @param  Incident   $incident
     * @return Response
     */
    public function approve(Request $request, Incident $incident)
    {
        $moderation_validation_required = $this->checkTypeOfModerationValidationRequired($request, $incident);

        $validator = $this->validateApproval($request, $moderation_validation_required);

        if($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $this->saveApprovalDecision($request, $incident);
        $this->logModerationDecision($request, $incident, $moderation_validation_required);

        if($incident->approved) {
            flash()->success('<strong>' . $incident->title . '</strong> has been approved.');
        } else {
            flash()->warning('<strong>' . $incident->title . '</strong> has been rejected.');
        }
        
        return back();
    }

    /**
     * Delete the incident.
     * 
     * @param  Incident   $incident
     * @return Response
     */
    public function delete(Incident $incident)
    {
        return view('admin.incidents-delete', compact('incident'));
    }

    /**
     * Delete the incident.
     * 
     * @param  Incident   $incident
     * @return Response
     */
    public function destroy(Incident $incident)
    {
        $incident->delete();

        flash()->success('Incident successfully deleted.');

        return redirect('/admin/incidents'); 
    }

    /**
     * Show the appropriate form given user's permissions.
     * 
     * @param  Int $id 
     * @return View
     */
    public function moderate(Incident $incident)
    {
        if(Auth::user()->can('edit-incidents')) {
            return view('admin.incidents-moderate-edit', compact('incident'));
        } else {
            return view('admin.incidents-moderate', compact('incident'));
        }
    }

    /**
     * Update the record.
     * 
     * @param  Request $request 
     * @return Response
     */
    public function update(Request $request, Incident $incident)
    {
       $moderation_validation_required = $this->checkTypeOfModerationValidationRequired($request, $incident);

       $validator = $this->getValidator($request, $moderation_validation_required);

       if($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
       }

        $this->saveInput($request, $incident);
        $this->saveApprovalDecision($request, $incident);
        $this->logModerationDecision($request, $incident, $moderation_validation_required);

        flash()->success('Incident has been successfully updated.');

        return back();
    }

    protected function checkTypeOfModerationValidationRequired(Request $request, Incident $incident)
    {
        $prior_decisions = $incident->moderation_decisions;

        // if no prior decisions, requires moderation with a comment only
        // when rejecting the incident
        if($prior_decisions->count() == 0) {
            return 'first';
        }

        // if a prior decision exists, how to validate changes depends on whether
        // the approval status changed
        $last_decision = $prior_decisions->pop();

        // if last decision is the same as this decision, and there is a comment, require moderation
        if($last_decision->approved == $request->approved && $request->moderation_comment) {
            return 'revisit';
        }        

        // if last decision is not the same as this decision, required moderation with decision
        if($last_decision->approved != $request->approved) {
            return 'revisit';
        }

        // otherwise, the last decision is the same as this decision, and there is no comment,
        // this does not require moderation
        return false;
    }

    /**
     * Log the moderation decision.
     * 
     * @param  Request  $request  
     * @param  Incident $incident 
     * @param  Boolean $skip
     * @return App\Decision;
     */
    protected function logModerationDecision(Request $request, Incident $incident, $moderation_validation_required = false)
    {
        if(! $moderation_validation_required) {
            return true;
        }

        $decision = new IncidentModerationDecision;
        $decision->incident_id = $incident->id;
        $decision->user_id = Auth::user()->id;
        $decision->approved = $request->approved;
        $decision->comment = $request->moderation_comment;
        $decision->save();

        return $decision;
    }

    /**
     * Approve the incident
     * 
     * @param  Request  $request  
     * @param  Incident $incident 
     * @return Void
     */
    protected function saveApprovalDecision(Request $request, Incident $incident)
    {
        $old_decision = $incident->approved;
        $new_decision = $request->approved;

        $decision_changed = $old_decision != $new_decision;

        if($decision_changed) {
            $incident->approved = $request->approved;
        }

        // if the decision changed to approve, and no prior email sent, email the submitter.
        if($decision_changed && $new_decision == true && ! $incident->approval_email_sent) {
            $incident->notify(new IncidentSubmissionApproved($incident));
            $incident->approval_email_sent = Carbon::now();
        }

        $incident->save();            

    }

    /**
     * Validated the moderation.
     * 
     * @param  Request $request [description]
     * @return Validator
     */
    protected function validateApproval(Request $request, $moderation_validation_required = false)
    {
        if($moderation_validation_required == 'revisit') {
             $rules = $this->moderation_rules_when_revisited;
             $messages = $this->moderation_messages_when_revisited;
        } else if($moderation_validation_required == 'first') {
             $rules = $this->moderation_rules_first;
             $messages = $this->moderation_messages_first;
        } else {
            $rules = [];
            $messages = [];
        }

        return Validator::make($request->all(), $rules, $messages);
    }

}
