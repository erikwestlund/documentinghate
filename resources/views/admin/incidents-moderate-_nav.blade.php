<div>
    <div class="col-sm-10 col-sm-offset-1 admin-incidents-nav {{ $location == 'top' ? 'top' : 'bottom' }}">
        <div class="col-sm-3">

            @if($incident->previous_unmoderated_incident_admin_url)
                <i class="fa fa-angle-double-left"></i> <a href="{{ $incident->previous_unmoderated_incident_admin_url }}">Previous unmoderated</a>
            @endif        
            
        </div>

        <div class="col-sm-2">
            <i class="fa fa-angle-left"></i> <a href="{{ $incident->previous_incident_admin_url }}">Previous</a>
        </div>

        <div class="col-sm-2 text-center">
            <a href="{{ url('/admin/incidents') }}">All Incidents</a>
        </div>

        <div class="col-sm-2 text-right">
            <a href="{{ $incident->next_incident_admin_url }}">Next</a> <i class="fa fa-angle-right"></i> 
        </div>    

        <div class="col-sm-3 text-right">

            @if($incident->next_unmoderated_incident_admin_url)
                <a href="{{ $incident->next_unmoderated_incident_admin_url }}">Next unmoderated</a> <i class="fa fa-angle-double-right"></i>
            @endif

        </div>    
    </div>
</div>