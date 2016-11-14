@extends('layouts.app')


@section('content')
    @include('admin._nav')

   <div class="col-sm-12 text-center bottom-margin-lg">

        <h1><i class="fa fa-cubes"></i> Incidents</h1>

    </div>

    <div class="col-sm-8 col-sm-offset-2" id="app">

        @include('flash::message')

        <table class="table table-hover">
            <tr>
                <th>Name</th>
                <th>E-mail</th>
                <th></th>
            </tr>

            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><a href="{{ url('/admin/users/' . $user->id) }}"> <i class="fa fa-edit"></i> Edit </a></td>
                
                </tr>
            @endforeach

        </table>

        {{ $users->links() }}


    </div>

@endsection
