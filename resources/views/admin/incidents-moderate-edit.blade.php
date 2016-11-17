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

        <form method="POST" action="{{ url('/admin/incidents/' . $incident->id ) }}" id="moderate-form" class="form-horizontal" enctype="multipart/form-data">
            @push('scripts_ready')
                $('#moderate-form').forkable();
                $('.date').datepicker({
                    format: 'yyyy-mm-dd'
                });
             @endpush

            {{ method_field('PATCH') }}            
            {{ csrf_field() }}

            <div class="form-group">
                <label for="title" class="col-sm-2 control-label">Title</label>
                <div class="col-sm-10">
                    <input type="text" name="title" class="form-control" id="title" placeholder="Title" value="{{ old('title', $incident->title) }}">
                </div>
            </div>

            <div class="form-group">
                <label for="date" class="col-sm-2 control-label">Date</label>
                <div class="col-sm-10">
                    <input type="text" name="date" class="date form-control" id="date" placeholder="Date" value="{{ old('date', $incident->date) }}">
                </div>
            </div>

            <div class="form-group">
                <label for="city" class="col-sm-2 control-label">City</label>
                <div class="col-sm-10">
                    <input type="text" name="city" class="form-control" id="city" placeholder="Title" value="{{ old('city', $incident->city) }}">
                </div>
            </div>

            <div class="form-group">
                <label for="state" class="col-sm-2 control-label">State</label>
                <div class="col-sm-10">
                    <select name="state" class="form-control" id="state">
                        @if(old('state'))
                            @foreach(config('constants.us_states') as $abbrev => $state)
                                <option value="{{ $abbrev }}"{{ old('state') == $abbrev ? ' selected' : '' }}>{{ $state }}</option>
                            @endforeach
                        @else
                            @foreach(config('constants.us_states') as $abbrev => $state)
                                <option value="{{ $abbrev }}"{{ $incident->state == $abbrev ? ' selected' : '' }}>{{ $state }}</option>
                            @endforeach                        
                        @endif
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="location_name" class="col-sm-2 control-label">Location name</label>
                <div class="col-sm-10">
                    <input type="text" name="location_name" class="form-control" id="location_name" placeholder="Title" value="{{ old('location_name', $incident->location_name) }}">
                </div>
            </div>

            <div class="form-group">
                <label for="source" class="col-sm-2 control-label">Source</label>
                <div class="col-sm-10">
                    @foreach($incident->source_dictionary as $source)
                        <div class="radio">
                            <label>
                                <input type="radio" name="source" id="source" value="{{ $source }}" 
                                    @if(old('source') == $source || $incident->source == $source)
                                        checked
                                    @endif
                                >
                                {{ title_case(str_replace('_', ' ', $source)) }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label for="incident_types" class="col-sm-2 control-label">Incident Types</label>
                <div class="col-sm-10">
                    @foreach($incident->incident_type_dictionary as $incident_type)
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="{{ $incident_type }}" id="{{ $incident_type }}" value="1" 
                                    @if(old($incident_type, $incident->{$incident_type}))
                                        checked
                                    @endif
                                >
                                {{ title_case(str_replace('_', ' ', $incident_type)) }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

                <div class="form-group">
                    <div class="col-sm-2 text-right bold">Photo</div>
                    <div class="col-sm-10">

                        <input type="file" name="photo" class="bottom-margin-md">
                        <div class="bottom-margin-md">Choose a photo to replace the current one.</div>

                        @if($incident->photo_url)
                            <div>
                                <a target="_blank" href="{{ $incident->photo_url }}">
                                    <img class="incident-thumbnail bottom-margin-sm" src="{{ $incident->photo_url }}">
                                </a>
                            </div>
                        @endif
                    </div>
                </div>


            <div class="form-group">
                <label for="description" class="col-sm-2 control-label">Descriptoin</label>
                <div class="col-sm-10">
                    <textarea name="description" class="form-control description" id="description" placeholder="Description">{{ old('description', $incident->description) }}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label for="ip" class="col-sm-2 control-label">IP</label>
                <div class="col-sm-10">
                    <input type="text" name="ip" class="form-control" id="ip" placeholder="Title" value="{{ old('ip', $incident->ip) }}">
                </div>
            </div>

            <div class="form-group">
                <label for="user_agent" class="col-sm-2 control-label">User Agent</label>
                <div class="col-sm-10">
                    <input type="text" name="user_agent" class="form-control" id="user_agent" placeholder="Title" value="{{ old('user_agent', $incident->user_agent) }}">
                </div>
            </div>

            @include('admin.incidents-moderate-_decision-form')

            <div class="form-group">
                <div class="col-sm-offset-1 col-sm-10">
                    <button type="submit" class="btn btn-success"><i class="fa fa-edit"></i> Edit</button>
                </div>
            </div>
        </form>

    </div>

    @include('admin.incidents-moderate-_nav', ['location' => 'bottom'])

@endsection
