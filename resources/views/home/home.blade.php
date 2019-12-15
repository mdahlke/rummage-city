@extends('layouts.full-width')

@section('content')
    <div class="background-helper"></div>

    <div class="flex-center full-height">
        <div class="content">
            <h1 class="title">
                Rummage City
            </h1>

            <search-box route="{{ route('listings.browse') }}"
                        query="{{ request('q') }}"
                        :show-filters="false"
            ></search-box>
        </div>
    </div>
@endsection
