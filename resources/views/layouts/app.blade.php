<html>
<head>
    @include('common/head')
</head>
<body class="page-{{ Route::currentRouteName() }}">

<div id="app">
    @include('common/main-navigation')
    @yield('main')
    @include('common/footer')
</div>

@include('common/scripts')

</body>
</html>
