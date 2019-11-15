@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
    <div class="card">
        <div class="card-header">Dashboard</div>

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            Welcome back {{ \Auth::user()->name }}!
        </div>
    </div>

    <div class="card">
        <div class="card-header">Your Listings</div>

        <div class="card-body">
            @foreach($activeListings as $listing)
                <a href="{{ route('user.listing.edit', ['listing' => $listing->id]) }}">
                    <h3><i class="fad fa-warehouse-alt"></i> {{ $listing->title }}
                    </h3>
                </a>
            @endforeach
        </div>
    </div>

    <div class="card">
        <div class="card-header">Saved Listings</div>

        <div class="card-body">
            @foreach($savedListings as $saved)

                <a href="{{ route('listings.view', [
                'address' => str_slug($saved->address),
                'listing' => $saved->id
                ]) }}">
                    <h3>
                        <i class="fad fa-heart"></i> {{ $saved->title }}
                    </h3>
                </a>
            @endforeach
        </div>
    </div>
@endsection
