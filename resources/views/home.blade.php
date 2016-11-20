@extends('layouts.app')

@section('content')

    <div class="container incidents">
        <div class="col-sm-12 text-center bottom-margin-md">
            <h1 class="title">{{ config('site.title') }}</h1>
        </div>

        <div class="col-sm-10 col-sm-offset-1 text-center panel panel-default top-padding-md bottom-padding-md bottom-margin-none">
            <div id="map" class="map"></div>
            @push('scripts_ready')
                // set up the map
                map = new L.Map('map');

                // create the tile layer with correct attribution
                var osmUrl='http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
                var osmAttrib='Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors';
                var osm = new L.TileLayer(osmUrl, {minZoom: 1, maxZoom: 40, attribution: osmAttrib});       

                // start the map in center of US
                map.setView(new L.LatLng(38.8282,-96.5795),4);
                map.addLayer(osm);

                @cache($geo_data)
                    @foreach($search ? $incidents : $geo_data as $geo_datum)
                        var marker_{{$geo_datum->id}} = L.marker([{{ $geo_datum->latitude }},{{ $geo_datum->longitude }}]).addTo(map);                    
                        marker_{{ $geo_datum->id }}.bindPopup('<div class="title"><a href="{{ $geo_datum->url }}"><strong>{{ $geo_datum->title }}</strong></a></div><div class="meta">{{ Carbon\Carbon::parse($geo_datum->date)->format('m/d/y') }}, {{ $geo_datum->location }}</div>');
                    @endforeach
                @endcache

            @endpush
        </div>

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
