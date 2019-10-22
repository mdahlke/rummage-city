@extends('layouts.full-width')

@section('content')
    @include('listings.partials.listing', ['listing' => $listing])
@endsection
