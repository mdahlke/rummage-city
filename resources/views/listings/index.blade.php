@extends('layouts.vue')

@section('title', 'Listings')

@section('main')
    <router-view :listings="{{ json_encode($listings) }}" :search="{{ $searchState }}"></router-view>
@endsection
