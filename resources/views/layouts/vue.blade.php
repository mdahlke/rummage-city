<html>
<head>
    @include('common/head')
</head>
<body>

<div id="app">
    @include('common/main-navigation')
    @yield('sub-nav')
    @yield('main')
</div>

@include('common/scripts')

</body>
</html>
