@extends('layouts.app')

@section('content')

    <div class="container incidents">
        <div class="col-sm-12 text-center bottom-margin-md">
            <h1 class="title">{{ config('site.title') }}</h1>
        </div>

        @include('_social-buttons', ['url' => env('APP_URL'), 'title' => config('site.title')])

        @include('_map')

        @include('_mini-menu')

        <div class="row">

            @if($search)
                <div class="col-sm-10 col-sm-offset-1 top-margin-sm bottom-padding-sm">
                    <span class="left-padding-sm top-margin-none showing-search-results">Search results for "{{ $search }}"  (<a href="{{ url('/') }}">Clear</a>)</span>
                </div>
            @endif

            <div class="col-sm-10 col-sm-offset-1">

                @if($search && $incidents->count() == 0)
                    <div class="col-sm-12 top-margin-sm top-border">
                        <h3>No results found.</h3>
                    </div>
                @endif

                @cache($incidents)

                    <table class="table">

                        @foreach($incidents as $incident)
                            <tr class="incident">
                                <td>

                                    @if($incident->thumbnail_photo_url)
                                        <img src="{{ $incident->thumbnail_photo_url }}" class="incident-image">
                                    @endif

                                    <h2 class="title"><a href="{{ $incident->url }}">{{ $incident->title }}</a></h2>
                                    <div class="location"><i class="fa fa-globe"></i> {{ $incident->location }}</div>
                                    
                                    <div class="date-source">

                                        <span class="date"><i class="fa fa-calendar"></i> {{ Carbon\Carbon::parse($incident->date)->format(config('site.date_format')) }}</span>
                                       
                                        @if($incident->source_html)
                                            <span class="source"> {!! $incident->source_html !!}</span>
                                        @endif

                                    </div>

                                    <div class="description">{!! $incident->short_description_html !!} </div>
                                </td>
                            </tr>
                        @endforeach                
                    </table>

                    {{ $incidents->links() }}

                @endcache

            </div>
        </div>
    </div>
@endsection
