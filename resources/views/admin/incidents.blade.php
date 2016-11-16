@extends('layouts.app')


@section('content')
    @include('admin._nav')

   <div class="col-sm-12 text-center bottom-margin-lg">

        <h1><i class="fa fa-cubes"></i> Incidents</h1>

    </div>

    <div class="col-sm-10 col-sm-offset-1" id="app">

        @include('flash::message')

        <admin-incident-table></admin-incident-table>

    </div>

@endsection
