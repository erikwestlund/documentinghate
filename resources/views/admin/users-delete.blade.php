@extends('layouts.app')


@section('content')
    @include('admin._nav')

   <div class="col-sm-12 text-center bottom-margin-lg">

        <h1><i class="fa fa-user-circle-o"></i> {{ $user->name }}</h1>

    </div>

    <div class="col-sm-8 col-sm-offset-2" id="app">

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form method="POST" action="{{ url('/admin/users/' . $user->id) }}" class="form-horizontal">
            {{ method_field('DELETE') }}            
            {{ csrf_field() }}


            <div class="form-group">
                <div class="col-sm-12 text-center">
                    <h3 class="bottom-margin-lg">Are you sure you want to delete this user?</h3>

                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Delete 'Em</button>
                    <a role="button" href="{{ url('/admin/users/' . $user->id ) }}" class="btn btn-default">Cancel</a>
                </div>
            </div>
        </form>




    </div>

@endsection
