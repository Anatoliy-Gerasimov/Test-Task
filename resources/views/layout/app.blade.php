<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
</head>
<body>
    <div id="app">
        <div class="container-fluid">
            @yield('page')
        </div>
    </div>

    @routes('api')
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
