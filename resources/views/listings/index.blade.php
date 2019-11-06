@extends('layouts.vue')

@section('title', 'Listings')

@section('sub-nav')
    <section class="search-bar sub-nav">
        <search-box route="{{ route('listings.browse') }}" query="{{ request('q') }}"></search-box>
    </section>
@endsection
