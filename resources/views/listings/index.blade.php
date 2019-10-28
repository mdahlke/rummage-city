@extends('layouts.app')

@section('title', 'Listings')

@include('listings.partials.scripts')

@section('main')
    <section class="listings-index">
        <map-listings :listings="{{ @json_encode($listings->all()) }}" :search="{{ $searchState }}"/>
    </section>
@endsection
