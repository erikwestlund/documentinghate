@extends('layouts.app')

@section('content')

    <div class="container incident">
        <div class="col-sm-10 col-sm-offset-1 text-center bottom-margin-md incident-title">
            <h1 class="title"><a href="{{ url('/') }}">{{ config('site.title') }}</a></h1>
        </div>

        @cache($incident)

            @include('_social-buttons', ['url' => $incident-> url , 'title' => $incident->title ])

            <div class="col-sm-10 col-sm-offset-1 text-center bottom-margin-lg top-padding-md top-border incident-meta">
                <h2 class="title">{{ $incident->title }}</h2>
            </div>

            <div class="col-sm-10 col-sm-offset-1 text-center">
                <div class="location">{{ $incident->location }}</div>
                
                <div class="date-source">

                    <span class="date"><i class="fa fa-calendar"></i> {{ Carbon\Carbon::parse($incident->date)->format(config('site.date_format')) }}</span>
                   
                    @if($incident->source_html)
                        <span class="source"> {!! $incident->source_html !!}</span>
                    @endif

                </div>
            </div>

            <div class="col-sm-10 col-sm-offset-1 top-margin-lg bottom-margin-lg">

                @if($incident->photo_url)
                    <a href="{{ $incident->photo_url }}" target="_blank"><img src="{{ $incident->photo_url }}"></a>
                @endif

                    <div class="description">{!! $incident->description_html !!}</div>

            </div>
        @endcache

        <div class="clearfix"></div>

        <div class="col-sm-10 col-sm-offset-1 top-margin-lg nav-menu">

            <div class="col-sm-3 text-left prev">
                @if($incident->previous_incident_url)
                    <a class="left-margin-sm" href="{{ $incident->previous_incident_url }}"> <i class="fa fa-arrow-circle-left"></i> <span class="nav-text">Previous</span></a>
                @endif
            </div>

            <div class="col-sm-6 text-center home">
                <a class="left-margin-sm" href="{{ url('/') }}"><i class="fa fa-home"></i> <span class="nav-text">Home</span></a>
            </div>

            <div class="col-sm-3 text-right next">
                <a class="right-margin-sm" href="{{ $incident->next_incident_url }}"><span class="nav-text">Next</span> <i class="fa fa-arrow-circle-right"></i></a> 
            </div>

        </div>

    </div>
@endsection
