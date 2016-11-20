<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Caching -->
    @if(Request::is('admin/*'))
        <meta name="turbolinks-cache-control" content="no-cache">
    @endif

    <title>{{ config('site.title') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ elixir('assets/css/app.css')}}">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>

    @if(Request::is('add'))
        <script src='https://www.google.com/recaptcha/api.js'></script>
    @endif
    

    <link rel="apple-touch-icon" sizes="180x180" href="https://s3.amazonaws.com/documentinghate/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="https://s3.amazonaws.com/documentinghate/favicon/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="https://s3.amazonaws.com/documentinghate/favicon/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="https://s3.amazonaws.com/documentinghate/favicon/manifest.json">
    <link rel="mask-icon" href="https://s3.amazonaws.com/documentinghate/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="theme-color" content="#ffffff">    
    <meta property="og:image" content="https://s3.amazonaws.com/documentinghate/images/fb.png"/>
    <meta property="og:image:secure_url" content="https://s3.amazonaws.com/documentinghate/images/fb.png" />
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:description" content="Documenation Hate is a crowd-sourced repository of incidents of hate in the United States." />

</head>
<body>
    <div id="app">
        @yield('content')
    </div>

    <script src="{{ elixir('assets/js/app.js') }}"></script>

    <script>
        $('div.alert').not('.alert-danger').delay(3000).fadeOut(350);
    </script>

    <script>
        $(document).ready(function(){
            @stack('scripts_ready')
        });
    </script>

</body>
</html>
