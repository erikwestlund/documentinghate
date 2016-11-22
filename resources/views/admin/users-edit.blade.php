@extends('layouts.app')


@section('content')
    @include('admin._nav')

   <div class="col-sm-12 text-center bottom-margin-lg">

        <h1><i class="fa fa-user-circle-o"></i> {{ $user->name }}</h1>

    </div>

    <div class="col-sm-8 col-sm-offset-2" id="app">

        @include('flash::message')
        @include('_errors')

        <form method="POST" action="{{ url('/admin/users/' . $user->id ) }}" class="form-horizontal">
            {{ method_field('PATCH') }}            
            {{ csrf_field() }}
            <div class="form-group">
                <label for="name" class="col-sm-3 control-label">Name</label>
                <div class="col-sm-9">
                    <input type="text" name="name" class="form-control" id="name" placeholder="Name" value="{{ old('name', $user->name) }}">
                </div>
            </div>


            <div class="form-group">
                <label for="email" class="col-sm-3 control-label">Email</label>
                <div class="col-sm-9">
                    <input type="email" name="email" class="form-control" id="email" placeholder="Email" value="{{ old('email', $user->email) }}">
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="col-sm-3 control-label">Password</label>
                <div class="col-sm-9">
                    <input type="password" name="password" class="form-control" id="password" placeholder="Leave blank to leave unchanged">
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="col-sm-3 control-label"></label>
                <div class="col-sm-9">
                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Confirm password">
                </div>
            </div>

            @permission('edit-users')
                <div class="form-group">
                    <label for="role" class="col-sm-3 control-label">Role</label>
                    <div class="col-sm-9">
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
            @endpermission

            @permission('receive-moderation-request-emails')
                <div class="form-group">
                    <label for="moderation_notification_frequency" class="col-sm-3 control-label">Notification Frequency</label>
                    <div class="col-sm-9">
                        <select name="moderation_notification_frequency" class="form-control" id="moderation_notification_frequency">
                            @if(old('moderation_notification_frequency'))
                                @foreach($user->notification_levels as $notification_level)
                                    <option value="{{ $notification_level }}"{{ old('moderation_notification_frequency') == $notification_level ? ' selected' : '' }}>{{ str_replace('_', ' ', title_case($notification_level)) }}</option>
                                @endforeach
                            @else
                                @foreach($user->notification_levels as $notification_level)
                                    <option value="{{ $notification_level }}"{{ $user->moderation_notification_frequency ==  $notification_level ? ' selected' : '' }}>{{ str_replace('_', ' ', title_case($notification_level)) }}</option>
                                @endforeach                        
                            @endif
                        </select>
                    </div>
                </div>

            @endpermission

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    @permission('delete-users')
                        <a role="button" type="submit" class="btn btn-default pull-right" href="{{ url('/admin/users/' . $user->id . '/delete') }}"><i class="fa fa-trash"></i> Delete This User</a>                
                    @endpermission

                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>

                </div>
            </div>
        </form>


    </div>

@endsection
