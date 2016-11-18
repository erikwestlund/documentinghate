@extends('layouts.app')

@section('content')

    <div class="container incident">
        <div class="col-sm-12 text-center bottom-margin-lg">
            <h1 class="title"><a href="{{ url('/') }}">{{ config('site.title') }}</a></h1>
        </div>

        @cache($incident)
            <div class="col-sm-12 text-center bottom-margin-lg top-padding-md top-border">
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

        <div class="col-sm-12 top-margin-lg nav-menu">

            <div class="col-sm-3 text-left">
                @if($incident->previous_incident_url)
                    <i class="fa fa-arrow-circle-left"></i> <a class="left-margin-sm" href="{{ $incident->previous_incident_url }}">Previous</a>
                @endif
            </div>

            <div class="col-sm-6 text-center">
                <i class="fa fa-home"></i> <a class="left-margin-sm" href="{{ url('/') }}">Home</a>
            </div>

            <div class="col-sm-3 text-right">
                <a class="right-margin-sm" href="{{ $incident->next_incident_url }}">Next</a> <i class="fa fa-arrow-circle-right"></i>
            </div>

        </div>

    </div>
@endsection
