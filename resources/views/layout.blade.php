<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('site.title') }}</title>
    <link rel="stylesheet" href="{{ elixir('assets/css/app.css')}}">
</head>

<body id="app">

    <div class="container">
        @yield('content')
    </div>

    <script src="{{ elixir('assets/js/app.js') }}"></script>


</body>

</html>