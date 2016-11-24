@if(! Request::is('about'))
    
    <div class="container footer">
        <div class="col-sm-10 col-sm-offset-1 top-border top-margin-md top-padding-md bottom-padding-lg">
            <a href="{{ secure_url('/about') }}"> About {{ config('site.title') }}</a>
        </div>
    </div>
@endif