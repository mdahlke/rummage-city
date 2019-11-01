@extends('layouts.full-width')

@section('title', $listing->title)

@section('content')
    @include('listings.partials.listing', ['listing' => $listing])
@endsection
