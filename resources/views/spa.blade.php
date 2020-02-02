<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="dns-prefetch" href="//ajax.googleapis.com"/>
    <link rel="dns-prefetch" href="//api.mapbox.com"/>
    <link rel="dns-prefetch" href="//fonts.googleapis.com"/>
    <link rel="dns-prefetch" href="//fonts.gstatic.com"/>

    <link rel="icon" type="image/png" href="/favicon.png"/>

{{--<link rel="manifest" href="/manifest.webmanifest">--}}
<!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,700,900|Dosis:400,600|Indie+Flower|Josefin+Sans:400,600&display=swap"
          rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/fontawesome.css') }}">


    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }
    </style>

    <script type="text/javascript">
        window.csrf_token = "{{ csrf_token() }}";
    </script>

</head>
<body>
<div id="app">
    <app></app>
</div>
<script src="{{ mix('js/manifest.js') }}"></script>
<script src="{{ mix('vendor.js') }}"></script>
<script src="{{ mix('js/app.js') }}"></script>

{{-- Vue DevTools --}}
{!! \App::environment('local') ?  '<script src="http://localhost:8098" defer></script>' : '' !!}
</body>
</html>
