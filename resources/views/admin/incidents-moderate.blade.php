@extends('layouts.app')


@section('content')
    @include('admin._nav')

   <div class="col-sm-12 text-center bottom-margin-lg">

        <div class="form-group pull-right">
            <div class="col-sm-12 text-center">
                <a role="button" type="submit" class="btn btn-default" href="{{ url('/admin/incidents/' . $incident->id . '/delete') }}"><i class="fa fa-trash"></i> Delete This Incident</a>
            </div>
        </div>

        <h1><i class="fa fa-user-cube"></i> {{ $incident->title }}</h1>

    </div>

    <div class="col-sm-10 col-sm-offset-2" id="app">

        @include('flash::message')

        <form method="POST" action="{{ url('/admin/incidents/' . $incident->id .'/approve' ) }}" id="moderate-form" class="form-horizontal">
            @push('scripts_ready')
                $('#moderate-form').forkable();
            @endpush

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
                    {{ $incident->location }} (<a target="_blank" href="{{ $incident->google_maps_url }}">Link</a>)
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
                        (<a target="_blank" href="mailto:{{ $incident->submitter_email }}">E-mail</a>)
                    @endif

                    @if($incident->source == 'social_media') 
                        (<a target="_blank" href="{{ $incident->social_media_url }}">Link</a>)
                    @endif
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 text-right bold">Incident Types</div>
                <div class="col-sm-8">
                    {{ $incident->incident_types }}

                    @if($incident->other)
                        ({{ $incident->other_incident_description }})
                    @endif
                </div>
            </div>

            @if($incident->photo_url)
                <div class="form-group">
                    <div class="col-sm-2 text-right bold">Photo</div>
                    <div class="col-sm-8">
                        
                        <a target="_blank" href="{{ $incident->photo_url }}">
                            <img class="incident-thumbnail" src="{{ $incident->thumbnail_photo_url ? $incident->thumbnail_photo_url : $incident->photo_url }}">
                        </a>

                    </div>
                </div>
            @endif

            <div class="form-group">
                <div class="col-sm-2 text-right bold">Description</div>
                <div class="col-sm-8">
                    {{ nl2br($incident->description) }}
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 text-right bold">IP</div>
                <div class="col-sm-8">
                    {{ $incident->ip }}
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 text-right bold">User Agent</div>
                <div class="col-sm-8">
                    {{ $incident->user_agent }}
                </div>
            </div>


            <div class="form-group top-margin-lg">
                <div class="col-sm-10 col-sm-offset-1">
                    <div class="has-success">
                        <div class="radio">
                            <label>
                                <input name="approve" type="radio" id="approve" value="1">
                                Approve this incident
                            </label>
                        </div>
                    </div>
                    <div class="has-error">
                        <div class="radio">
                            <label>
                                <input name="approve" type="radio" id="disapprove" value="0">
                                Disapprove this incident
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group field hidden" data-parent-branch="approve" data-show-on-value="0">
                <div class="col-sm-10 col-sm-offset-1">
                    <input type="text" class="form-control" placeholder="Reason for disapproval">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-1 col-sm-10">
                    <button type="submit" class="btn btn-success"><i class="fa fa-check-square"></i> Submit</button>
                </div>
            </div>
        </form>

    </div>

@endsection
