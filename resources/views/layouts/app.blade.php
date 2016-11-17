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
</head>
<body>
    <div id="app">
        @yield('content')
    </div>

    <script src="{{ elixir('assets/js/app.js') }}"></script>

    <script>
        $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
    </script>

    <script>
        $(document).ready(function(){

            @stack('scripts_ready')

        });
    </script>
</body>
</html>
