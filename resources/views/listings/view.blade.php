@extends('layouts.vue')

@section('title', $listing->title)

@section('main')
    <router-view :listing="{{ json_encode($listing) }}"></router-view>
@endsection
