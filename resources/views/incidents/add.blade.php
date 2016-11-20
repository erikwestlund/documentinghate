@extends('layouts.app')

@section('content')

    <div class="col-sm-10 col-sm-offset-1 text-center bottom-margin-sm bottom-padding-md add-incident">
        <h1 class="title"><a href="{{ url('/') }}">{{ config('site.title') }}</a></h1>
    </div>

    <div class="col-sm-12" id="app">
        <document-incident></document-incident>
    </div>

@endsection
