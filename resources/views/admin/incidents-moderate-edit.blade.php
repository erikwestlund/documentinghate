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

                var simplemde = new SimpleMDE({
                    element: document.getElementById("description"),
                    hideIcons: ["guide", "heading", "image"],
                });

                $('#moderate-form').forkable({ });

                if($('input#other').is(':checked')) {
                    $(".other-incident-description-container").removeClass('hidden');                    
                }

                $('input#other').click(function() {
                    if( $(this).is(':checked')) {
                        $(".other-incident-description-container").removeClass('hidden');
                    } else {
                        $(".other-incident-description-container").addClass('hidden');
                    }
                }); 

                $('.date').datetimepicker({
                    format: 'YYYY-MM-DD'
                });

                $('.approval_email_sent').datetimepicker({
                    format: 'YYYY-MM-DD hh:mm:ss'
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
                <label for="slug" class="col-sm-2 control-label">Slug</label>
                <div class="col-sm-10">
                    <input type="text" name="slug" class="form-control" id="slug" placeholder="Slug" value="{{ old('slug', $incident->slug) }}">
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
                    <input type="text" name="city" class="form-control" id="city" placeholder="City" value="{{ old('city', $incident->city) }}">
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
                                    @if((!is_null(old('source')) && old('source') == $source) || (is_null(old('source')) && $incident->source == $source))
                                        checked
                                    @endif
                                >
                                {{ title_case(str_replace('_', ' ', $source)) }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Show only if other is chosen --}}
            <div class="form-group

                @if((!is_null(old('source')) && old('source') != 'other') || (is_null(old('source')) && $incident->source != 'other'))
                    hidden
                @endif

            " data-parent-branch="source" data-show-on-value="other">
                <label for="source_other_description" class="col-sm-2 control-label">Other Source</label>
                <div class="col-sm-10">
                    <input type="text" name="source_other_description" class="form-control" id="source_other_description" placeholder="How learned of incident" value="{{ old('source_other_description', $incident->source_other_description) }}">
                </div>
            </div>

            {{-- Show only if news article chosen --}}
            <div class="form-group

                @if((!is_null(old('source')) && old('source') != 'news_article') || (is_null(old('source')) && $incident->source != 'news_article'))
                    hidden
                @endif

            " data-parent-branch="source" data-show-on-value="news_article">
                <label for="news_article_url" class="col-sm-2 control-label">News article URL</label>
                <div class="col-sm-10">
                    <input type="text" name="news_article_url" class="form-control" id="news_article_url" placeholder="News Article URL" value="{{ old('news_article_url', $incident->news_article_url) }}">
                </div>
            </div>

            {{-- Show only if social media is chosen --}}
            <div class="form-group

                @if((!is_null(old('source')) && old('source') != 'social_media') || (is_null(old('source')) && $incident->source != 'social_media'))
                    hidden
                @endif

            " data-parent-branch="source" data-show-on-value="social_media">
                <label for="social_media_url" class="col-sm-2 control-label">Social media post URL</label>
                <div class="col-sm-10">
                    <input type="text" name="social_media_url" class="form-control" id="social_media_url" placeholder="Social Media URL" value="{{ old('social_media_url', $incident->social_media_url) }}">
                </div>
            </div>

            {{-- Show only if other is checked --}}
            <div class="form-group other-incident-description-container hidden">
                <label for="other_incident_description" class="col-sm-2 control-label">Other description</label>
                <div class="col-sm-10">
                    <input type="text" name="other_incident_description" class="form-control" id="other_incident_description" placeholder="Brief description of the incident" value="{{ old('other_incident_description', $incident->other_incident_description) }}">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 text-right bold">Photo</div>
                <div class="col-sm-10">

                    <input type="file" name="photo" class="bottom-margin-md">
                    <div class="bottom-margin-md">Choose a photo to replace the current one or check the below box to remove the photo.</div>

                </div>
            </div>

            @if($incident->photo_url)
                <div class="form-group">
                    <div class="col-sm-8 col-sm-offset-2">
                        <div>
                            <a target="_blank" href="{{ $incident->photo_url }}">
                                <img class="incident-thumbnail bottom-margin-sm" src="{{ $incident->photo_url }}">
                            </a>
                        </div>
                    </div>
                </div>

            <div class="form-group">
                <label for="remove_photo" class="col-sm-2 control-label"></label>
                <div class="col-sm-10">
                    <div class="checkbox">

                        <label>
                            <input type="checkbox" name="remove_photo" id="remove_photo" value="true" 
                                @if((!is_null(old('remove_photo')) && old('remove_photo') == true))
                                    checked
                                @endif
                            >

                            Remove this photo                            
                        </label>
                    </div>
                </div>
            </div>

            @endif



            <div class="form-group">
                <label for="description" class="col-sm-2 control-label">Description</label>
                <div class="col-sm-10">
                    <textarea name="description" class="form-control description" id="description" placeholder="Description">{{ old('description', $incident->description) }}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label for="submitter_email" class="col-sm-2 control-label">Submitter E-mail</label>
                <div class="col-sm-10">
                    <input type="text" name="submitter_email" class="form-control" id="submitter_email" placeholder="Submitter Email Address" value="{{ old('submitter_email', $incident->submitter_email) }}">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="email_when_approved" id="email_when_approved" value="true"
                                @if(old('email_when_approved', $incident->email_when_approved))
                                    checked
                                @endif
                            >

                            Email user when approved

                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="approval_email_sent" class="col-sm-2 control-label">Approval Email Sent</label>
                <div class="col-sm-10">
                    <input type="text" name="approval_email_sent" class="approval_email_sent form-control" id="approval_email_sent" placeholder="Approval Email Sent" value="{{ old('approval_email_sent', $incident->approval_email_sent) }}">
                </div>
            </div>

            <div class="form-group">
                <label for="ip" class="col-sm-2 control-label">IP</label>
                <div class="col-sm-10">
                    <input type="text" name="ip" class="form-control" id="ip" placeholder="IP" value="{{ old('ip', $incident->ip) }}">
                </div>
            </div>

            <div class="form-group">
                <label for="user_agent" class="col-sm-2 control-label">User Agent</label>
                <div class="col-sm-10">
                    <input type="text" name="user_agent" class="form-control" id="user_agent" placeholder="User Agent" value="{{ old('user_agent', $incident->user_agent) }}">
                </div>
            </div>

            @include('admin.incidents-moderate-_decision-form')

            <div class="form-group">
                <div class="col-sm-offset-1 col-sm-10">
                    @permission('delete-incidents')                
                        <a role="button" type="submit" class="btn btn-default pull-right" href="{{ url('/admin/incidents/' . $incident->id . '/delete') }}"><i class="fa fa-trash"></i> Delete This Incident</a>                
                    @endpermission

                    <button type="submit" class="btn btn-success"><i class="fa fa-edit"></i> Edit</button>
                </div>
            </div>
        </form>

    </div>

    @include('admin.incidents-moderate-_nav', ['location' => 'bottom'])

    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>

@endsection
