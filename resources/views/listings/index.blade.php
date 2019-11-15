@extends('layouts.vue')

@section('title', 'Listings')

@section('main')
    <router-view :listings="{{ json_encode($listings) }}" :search="{{ json_encode($searchState) }}"></router-view>
@endsection
