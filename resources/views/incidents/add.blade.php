@extends('layout')

@section('content')
    <div class="col-sm-12 text-center bottom-margin-lg">

        <h1>Report an Incident</h1>

    </div>

    <div class="col-sm-12">
        <form class="form-horizontal">

            <div class="col-sm-offset-1 col-sm-11 bottom-margin-md">
                <h2>Where did it happen?</h2>
            </div>

            <div class="clear"></div>

            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-success"><i class="fa fa-plus-circle"></i> Report It</button>
                </div>
            </div>
        </form>
    </div>
@endsection
