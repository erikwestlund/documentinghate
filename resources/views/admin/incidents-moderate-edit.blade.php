<form method="POST" action="{{ url('/admin/incidents/' . $incident->id ) }}" class="form-horizontal">
    {{ method_field('PATCH') }}            
    {{ csrf_field() }}

    <div class="form-group">
        <div class="col-sm-2 text-right bold">Title</div>
        <div class="col-sm-8">
            {{ $incident->title }}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2 text-right bold">Date</div>
        <div class="col-sm-8">
            {{ $incident->date }}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2 text-right bold">Location</div>
        <div class="col-sm-8">
            {{ $incident->location }}

            @if($incident->google_maps_place_id) 
                <a href="{{ $incident->google_maps_url }}"><i class="fa fa-globe fa-lg"></i></a>
            @endif
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default"><i class="fa fa-edit"></i> Edit</button>
        </div>
    </div>
</form>