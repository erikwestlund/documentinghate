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
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2 text-right bold">Source</div>
        <div class="col-sm-8">
            {{ $incident->source_processed }}

            @if($incident->source == 'news') 
                (<a target="_blank" href="{{ $incident->source_url }}">Link</a>)
            @endif

            @if(in_array($incident->source, ['witness', 'someone_else_witnessed', 'happened_to_me'])) 
                (<a target="_blank" href="{{ $incident->source_url }}">Link</a>)
            @endif

            @if($incident->source == 'social_media') 
                (<a target="_blank" href="{{ $incident->social_media_url }}">Link</a>)
            @endif
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2 text-right bold">Description</div>
        <div class="col-sm-8">

            @if($incident->location) 
                <iframe
                  width="300"
                  height="300"
                  frameborder="0" style="border:0; float: right;"
                  src="https://www.google.com/maps/embed/v1/place?key={{ config('googlemaps.key') }}&q={{ $incident->location }}&zoom=4" allowfullscreen>
                </iframe>            
            @endif

            {{ nl2br($incident->description) }}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default"><i class="fa fa-edit"></i> Edit</button>
        </div>
    </div>
</form>