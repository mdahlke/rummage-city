@extends('layouts.full-width')

@section('content')

    <section class="search-banner">

        <div class="background-helper"></div>

        <div class="container h-100">
            <div class="row align-items-center h-100">
                <div class="col-12">
                    <div class="content text-center">
                        <h1 class="title">
                            Find your next sale!
                        </h1>

                        <search-box route="{{ route('listings.browse') }}"
                                    query="{{ request('q') }}"
                                    :show-filters="false"
                        ></search-box>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <div class="container">
        <listings-recent :listings="{{ @json_encode($recentListings) }}"/>
    </div>
@endsection
