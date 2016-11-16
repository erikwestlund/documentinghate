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

        @if(Auth::user()->can('edit-incidents'))
            @include('admin.incidents-moderate-show')
        @else
            @include('admin.incidents-moderate-show')
        @endif

    </div>

@endsection
