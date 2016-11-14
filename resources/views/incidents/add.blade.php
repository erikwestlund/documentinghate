@extends('layouts.app')

@section('content')
    <div class="col-sm-12 text-center bottom-margin-lg">

        <h1>Document an Incident</h1>

    </div>

    <div class="col-sm-12" id="app">
        <document-incident></document-incident>
    </div>

    @push('scripts_ready')
        $('select.state').on('change', function(){ 

{{--             if ($(this).val() === "") {
                $(this).addClass('placeholder'); 
            } else { 
                $(this).removeClass('placeholder');
            }
 --}}
        });
    @endpush

@endsection
