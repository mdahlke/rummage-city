<section id="main-navigation">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand" href="{{ route('home') }}">Rummage City</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('listings.browse') }}"
                           role="button"
                           aria-expanded="false">Browse Sales</a>
                    </li>

                </ul>

                <ul class="navbar-nav flex-row mr-lg-0">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle mr-3 mr-lg-0" id="navbarDropdownMenuLink"
                               data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                <img class="rounded-circle"
                                     src="https://www.gravatar.com/avatar/{{ md5(Auth::user()->email) }}?size=40"
                                     alt="{{ Auth::user()->email }}">
                                <span class="caret"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="fad fa-alicorn"></i> {{ __('Dashboard') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('user.listing.new') }}">
                                    <i class="far fa-plus-circle"></i> {{ __('Add Listing') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="fal fa-user-circle"></i> {{ __('Account') }}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>

                {{--        <search-box route="{{ route('listings.browse') }}" query="{{ request('q') }}"></search-box>--}}
            </div>
        </nav>
    </div>
</section>
