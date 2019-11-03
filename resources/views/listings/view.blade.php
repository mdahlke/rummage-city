@extends('layouts.vue')

@section('title', $listing->title)

@section('main')
    {{--    @include('listings.partials.listing', ['listing' => $listing])--}}
    <listing :listing="{{ $listing }}"></listing>
@endsection
