@extends('layouts.app')

@section('content')
    <div class="container incidents">
        <div class="col-sm-12 text-center bottom-margin-lg">
            <h1 class="title">{{ config('site.title') }}</h1>
        </div>

        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">

                @cache($incidents)

                    <table class="table">

                        @foreach($incidents as $incident)
                            <tr class="incident">
                                <td>

                                    @if($incident->thumbnail_photo_url)
                                        <img src="{{ $incident->thumbnail_photo_url }}">
                                    @endif

                                    <h2 class="title"><a href="{{ url('/incidents/' . $incident->slug )}}">{{ $incident->title }}</a></h2>
                                    <div class="location">{{ $incident->location }}</div>
                                    
                                    <div class="date-source">

                                        <span class="date"><i class="fa fa-calendar"></i> {{ Carbon\Carbon::parse($incident->date)->format(config('site.date_format')) }}</span>
                                       
                                        @if($incident->source_html)
                                            <span class="source"> {!! $incident->source_html !!}</span>
                                        @endif

                                    </div>

                                    <div class="description">{!! $incident->short_description_html !!}</div>
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
