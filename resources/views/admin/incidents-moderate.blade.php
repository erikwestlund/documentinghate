@extends('layouts.app')


@section('content')
    @include('admin._nav')

    @include('admin.incidents-moderate-_nav', ['location' => 'top'])


   <div class="col-sm-10 col-sm-offset-1 text-center bottom-margin-lg">

        <h1><i class="fa fa-user-cube"></i> {{ $incident->title }}</h1>

    </div>

    <div class="col-sm-10 col-sm-offset-1" id="app">

        @include('flash::message')
        @include('_errors')

        <form method="POST" action="{{ url('/admin/incidents/' . $incident->id .'/approve' ) }}" id="moderate-form" class="form-horizontal">
            @push('scripts_ready')
                $('#moderate-form').forkable();
            @endpush

            {{ method_field('PATCH') }}            
            {{ csrf_field() }}

            <div class="form-group">
                <div class="col-sm-2 text-right bold">Title</div>
                <div class="col-sm-10">
                    {{ $incident->title }}
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 text-right bold">Date</div>
                <div class="col-sm-10">
                    {{ $incident->date }}
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 text-right bold">Location</div>
                <div class="col-sm-10">
                    {{ $incident->location }} (<a target="_blank" href="{{ $incident->google_maps_url }}">Link</a>)
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 text-right bold">Source</div>
                <div class="col-sm-10">
                    {{ $incident->source_processed }}

                    @if($incident->source == 'news') 
                        (<a target="_blank" href="{{ $incident->source_url }}">Link</a>)
                    @endif

                    @if(in_array($incident->source, ['witness', 'someone_else_witnessed', 'happened_to_me'])) 
                        (<a target="_blank" href="mailto:{{ $incident->submitter_email }}">E-mail</a>)
                    @endif

                    @if($incident->source == 'social_media') 
                        (<a target="_blank" href="{{ $incident->social_media_url }}">Link</a>)
                    @endif
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 text-right bold">Incident Types</div>
                <div class="col-sm-10">
                    {{ $incident->incident_types }}

                    @if($incident->other)
                        ({{ $incident->other_incident_description }})
                    @endif
                </div>
            </div>

            @if($incident->photo_url)
                <div class="form-group">
                    <div class="col-sm-2 text-right bold">Photo</div>
                    <div class="col-sm-10">
                        
                        <a target="_blank" href="{{ $incident->photo_url }}">
                            <img class="incident-thumbnail" src="{{ $incident->thumbnail_photo_url ? $incident->thumbnail_photo_url : $incident->photo_url }}">
                        </a>

                    </div>
                </div>
            @endif

            <div class="form-group">
                <div class="col-sm-2 text-right bold">Description</div>
                <div class="col-sm-10">
                    {!! nl2br(e($incident->description)) !!}
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 text-right bold">IP</div>
                <div class="col-sm-10">
                    {{ $incident->ip }}
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 text-right bold">User Agent</div>
                <div class="col-sm-10">
                    {{ $incident->user_agent }}
                </div>
            </div>

            @include('admin.incidents-moderate-_decision-form')

            <div class="form-group">
                <div class="col-sm-offset-1 col-sm-10">
                    <button type="submit" class="btn btn-success"><i class="fa fa-check-square"></i> Submit</button>
                </div>
            </div>
        </form>

    </div>

    @include('admin.incidents-moderate-_nav', ['location' => 'bottom'])

@endsection
