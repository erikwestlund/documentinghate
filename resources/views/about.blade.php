@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="col-sm-12 text-center bottom-margin-md">
            <h1 class="title"><a href="{{ url('/') }}">{{ config('site.title') }}</a></h1>
        </div>

            @include('_social-buttons', ['url' => secure_url('/about'), 'title' => 'About ' . config('site.title')])

            <div class="col-sm-10 col-sm-offset-1 bottom-margin-md top-padding-md top-border">
                <h2 class="title">About Us</h2>
            </div>

            <div class="col-sm-10 col-sm-offset-1">
          
                <p>{{ config('site.description') }}.</p>

                <p>All incidents are moderated by a volunteer staff.</p>

                <p>Questions about {{ config('site.title') }} should be sent to <a href="mailto:{{ config('site.admin_email') }}">{{ config('site.admin_email') }}</a>.</p>
            </div>

            <div class="col-sm-10 col-sm-offset-1 top-margin-lg">
          
                <h4>Technologies Used</h4>

                <p>{{ config('site.title') }} was developed in <a href="https://php.net/">PHP</a> using the <a href="https://laravel.com/">Laravel Framework</a>.</p>

                <p>The site proudly leverages these free, open-source technologies:</p>

                <ul>
                    <li><a href="https://mariadb.org">Maria DB</a></li>
                    <li><a href="https://redis.io">Redis</a></li>
                    <li><a href="http://kr.github.io/beanstalkd/">Beanstalkd</a></li>
                    <li><a href="https://www.elastic.co/">Elasticsearch</a></li>
                    <li><a href="http://leafletjs.com/">Leaflet</a></li>
                    <li><a href="http://vuejs.com/">Vue.js</a></li>
                </ul>

                <p>We are hosted on <a href="https://linode.com">Linode</a> with the help of <a href="https://aws.amazon.com/s3/">Amazon S3</a>.</p>

            </div>


    </div>
@endsection
