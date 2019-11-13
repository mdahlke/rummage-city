@extends('layouts.app')

@section('main')
    <div id="account-area" class="container-edge">
        <div class="row no-gutters">
            <div class="col-xs col-md-3 col-lg-2 justify-self-center">
                <aside id="dashboard-navigation">
                    <section class="profile-info text-center">
                        @section('sidebar')
                            <img class="rounded-circle"
                                 src="https://www.gravatar.com/avatar/{{ md5(Auth::user()->email) }}"
                                 alt="{{ Auth::user()->email }}">
                            <h6>{{ Auth::user()->name }}</h6>
                            <div class="nav-links">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <a href="{{ route('dashboard') }}"><i class="far fa-tachometer"></i>
                                            Dashboard</a>
                                    </li>
                                    <li class="list-group-item">
                                        <a href="{{ route('listings.browse') }}"><i class="far fa-list"></i> My Listings</a>
                                    </li>
                                    <li class="list-group-item">
                                        <a href="{{ route('listings.browse') }}#saved"><i class="fas fa-heart"></i>
                                            Saved Listings</a>
                                    </li>
                                    <li class="list-group-item">
                                        <a href="{{ route('listings.browse') }}#near-me"><i class="far fa-location"></i>
                                            Sales Near Me</a>
                                    </li>
                                    <li class="list-group-item">
                                        <a href="{{ route('user.settings') }}"><i class="far fa-cogs"></i> Settings</a>
                                    </li>
                                    <li class="list-group-item">
                                        <a href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            <i class="far fa-power-off"></i> Logout</a>
                                    </li>
                                </ul>
                            </div>
                        @show
                    </section>
                </aside>
            </div>

            <div class="col-xs col-sm-12 col-md">
                <main class="main">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>
@endsection
