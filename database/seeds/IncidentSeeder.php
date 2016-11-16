<?php

use Illuminate\Database\Seeder;

class IncidentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $incidents = factory(App\Incident::class, 100)->create();

         foreach($incidents as $incident) {


            if($incident->source != 'news') {
                $incident->source_url = '';
            }

            if($incident->source != 'social_media') {
                $incident->social_media_url = '';
            }
            
            if($incident->source != 'other') {
                $incident->source_other_description = '';
            }

            if($incident->source == 'news' || $incident->source == 'social_media') {
                $incident->submitter_email = '';
            }


            if($incident->other_incident_type ==0) {
                $incident->other_incident_description = '';
            }

            if(rand(1,5) == 1) {
                $incident->approved = null;
                $incident->approved_by_user_id = null;
                $incident->approval_decision_comment = null;
            }

            $incident->save();
         }

    }
}
