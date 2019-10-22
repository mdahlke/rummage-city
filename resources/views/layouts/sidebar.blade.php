@extends('layouts.app')

@section('main')
    <div class="container-fluid">
        <div class="row">
            <div class="col col-md-3">
                <aside>
                    @section('sidebar')
                        This is the master sidebar.
                    @show
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
