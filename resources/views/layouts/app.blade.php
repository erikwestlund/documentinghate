<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>

        @if(Request::is('add'))
            {{ config('site.title') }} | Make a Submission
        @elseif(Request::is('about'))
            {{ config('site.title') }} | About
        @elseif(isset($incident) && Request::is('incidents/*'))
            {{ $incident->title }}
        @else
            {{ config('site.title') }} | {{ config('site.tagline') }}
        @endif

    </title>

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

    @if(isset($incident) && Request::is('incidents/*'))
        <meta property="og:title" content="{{ $incident->title }}">        
        <meta property="og:description" content="{{ str_limit($incident->description, 300) }}">        
        <meta property="og:url" content="{{ $incident->url }}">
    @else
        <meta property="og:tot;e" content="{{ config('site.title') }}">
        <meta property="og:description" content="{{ config('site.description') }}">
        <meta property="og:url" content="{{ env('APP_URL') }}">
    @endif

    @if(isset($incident) && Request::is('incidents/*') && $incident->photo_url)
        <meta property="og:image" content="{{ $incident->photo_url }}">
        <meta property="og:image:secure_url" content="{{ $incident->photo_url }}">
    @else
        <meta property="og:image" content="https://s3.amazonaws.com/documentinghate/images/fb.png">
        <meta property="og:image:secure_url" content="https://s3.amazonaws.com/documentinghate/images/fb.png">
        <meta property="og:image:type" content="image/png">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
    @endif
    

</head>
<body>
    <div id="app">
        @yield('content')

        @include('_footer')
    </div>

    
    @if(Request::is('admin*'))
        <script src="{{ elixir('assets/js/admin.js') }}"></script>

        <script>
            $('div.alert').not('.alert-danger').delay(3000).fadeOut(350);
        </script>

        <script>
            $(document).ready(function(){
                @stack('scripts_ready')
            });
        </script>
    @else
        <script src="{{ elixir('assets/js/app.js') }}"></script>

        <script>
            document.addEventListener("DOMContentLoaded", function(event) {                 
                @stack('scripts_ready')
            });
        </script>
    @endif

    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-22785728-8', 'auto');
      ga('send', 'pageview');

    </script>

</body>
</html>
