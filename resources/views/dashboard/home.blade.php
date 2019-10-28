@extends('layouts.dashboard')

@section('content')
    <div class="card">
        <div class="card-header">Dashboard</div>

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            You are logged in!
        </div>
    </div>

    <div class="card">
        <div class="card-header">Your Listings</div>

        <div class="card-body">
            @foreach(Auth::user()->listing as $listing)
                <a href="{{ route('listings.edit', ['listing' => $listing->id]) }}">
                    <h3><i class="fad fa-{{ $listing->hasActiveDate()? 'check' : 'ban' }}"></i> {{ $listing->title }}
                    </h3>
                </a>
            @endforeach
        </div>
    </div>

    <div class="card">
        <div class="card-header">Saved Listings</div>

        <div class="card-body">
            @foreach(Auth::user()->savedListing as $saved)
                <a href="{{ route('listings.view', ['listing' => $saved->listing->id]) }}">
                    <h3>
                        <i class="fad fa-{{ $saved->listing->hasActiveDate()? 'check' : 'ban' }}"></i> {{ $saved->listing->title }}
                    </h3>
                </a>
            @endforeach
        </div>
    </div>
@endsection
