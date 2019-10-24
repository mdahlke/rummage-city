@extends('layouts.full-width')

@include('listings.partials.scripts')

@section('content')

    <a href="{{ route('dashboard') }}">&larr; Back to dashboard</a>

    <form class="form dropzone" id="listing-edit-dropzone" action="{{ $route }}" method="post">
        @csrf

        <div class="form-group">
            <label for="title">Title</label>
            <input id="title" class="form-control" type="text" name="title" value="{{ $listing->title }}">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" class="form-control" name="description">{{ $listing->description }}</textarea>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <map-geocode address="{{ $listing->address }}"
                         latitude="{{ $listing->latitude }}"
                         longitude="{{ $listing->longitude }}" />
        </div>

        <listing-dates-input :dates="{{ $listing->date }}"></listing-dates-input>

        <div class="row align-items-center justify-content-between">
            @foreach($listing->image as $image)
                <div class="col-2">
                    <img class="img-fluid ml-2 mb-2" src="{{ asset('storage/'. $image->path) }}" />
                </div>
            @endforeach

        </div>
        <div id="dropzone-previews">
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Post</button>
        </div>
    </form>
@endsection

