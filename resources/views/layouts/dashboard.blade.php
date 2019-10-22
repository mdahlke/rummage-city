@extends('layouts.app')

@section('main')
    <div class="container-fluid">
        <div class="row">
            <div class="col col-md-3 justify-self-center">
                <aside>
                    <section class="profile-info text-center">
                        @section('sidebar')
                            <img class="rounded-circle"
                                 src="https://www.gravatar.com/avatar/{{ md5(Auth::user()->email) }}"
                                 alt="{{ Auth::user()->email }}">
                            <h6>{{ Auth::user()->name }}</h6>
                        @show
                    </section>
                </aside>
            </div>

            <div class="col col-md-9">
                <main>
                    @yield('content')
                </main>
            </div>
        </div>
    </div>
@endsection
