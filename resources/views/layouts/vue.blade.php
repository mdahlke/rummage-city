<html>
<head>
    @include('common/head')
</head>
<body>

<div id="app">
    @include('common/main-navigation')
    @yield('sub-nav')
    <router-view>
        @yield('main')
    </router-view>
    @include('common/footer')
</div>

@include('common/scripts')

</body>
</html>
