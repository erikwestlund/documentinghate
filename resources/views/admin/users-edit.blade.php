@extends('layouts.app')


@section('content')
    @include('admin._nav')

   <div class="col-sm-12 text-center bottom-margin-lg">

        <div class="form-group pull-right">
            <div class="col-sm-12 text-center">
                <a role="button" type="submit" class="btn btn-default" href="{{ url('/admin/users/' . $user->id . '/delete') }}"><i class="fa fa-trash"></i> Delete This User</a>
            </div>
        </div>

        <h1><i class="fa fa-user-circle-o"></i> {{ $user->name }}</h1>

    </div>

    <div class="col-sm-8 col-sm-offset-2" id="app">

        @include('flash::message')
        @include('_errors')

        <form method="POST" action="{{ url('/admin/users/' . $user->id ) }}" class="form-horizontal">
            {{ method_field('PATCH') }}            
            {{ csrf_field() }}
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Name</label>
                <div class="col-sm-10">
                    <input type="text" name="name" class="form-control" id="name" placeholder="Name" value="{{ old('name', $user->name) }}">
                </div>
            </div>


            <div class="form-group">
                <label for="email" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" name="email" class="form-control" id="email" placeholder="Email" value="{{ old('email', $user->email) }}">
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="col-sm-2 control-label">Password</label>
                <div class="col-sm-10">
                    <input type="password" name="password" class="form-control" id="password" placeholder="Leave blank to leave unchanged">
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="col-sm-2 control-label"></label>
                <div class="col-sm-10">
                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Confirm password">
                </div>
            </div>

            <div class="form-group">
                <label for="role" class="col-sm-2 control-label">Role</label>
                <div class="col-sm-10">
                    <select name="role" class="form-control" id="role">
                        @if(old('role'))
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}"{{ old('role') == $role->name ? ' selected' : '' }}>{{ $role->display_name }}</option>
                            @endforeach
                        @else
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}"{{ $user->hasRole($role->name) ? ' selected' : '' }}>{{ $role->display_name }}</option>
                            @endforeach                        
                        @endif
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default"><i class="fa fa-edit"></i> Edit</button>
                </div>
            </div>
        </form>


    </div>

@endsection
