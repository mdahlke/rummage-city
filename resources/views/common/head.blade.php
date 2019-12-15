<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>@yield('title') | Rummage City</title>

<link rel="icon" type="image/png" href="/favicon.png"/>

{{--<link rel="manifest" href="/manifest.webmanifest">--}}
<!-- Fonts -->
<link href="https://fonts.googleapis.com/css?family=Raleway:100,600|Dosis:400,600|Indie+Flower|Josefin+Sans:400,600&display=swap"
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
    window.csrf_token = "{{ csrf_token() }}"
</script>

{{-- Vue DevTools --}}
{!! \App::environment('local') ?  '<script src="http://localhost:8098"></script>' : '' !!}

@stack('head')
