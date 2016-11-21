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