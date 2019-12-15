@extends('layouts.full-width')

@section('title', 'Edit: '. $listing->title)

@include('listings.partials.scripts')

@section('content')

    <div class="container mt-5 mb-5">

        <div class="row">
            <div class="col-12">
                <a href="{{ route('dashboard') }}">&larr; Back to dashboard</a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">

                <form class="form dropzone" id="listing-edit-dropzone" action="{{ $route }}" method="post">
                    @csrf

                    <div class="form-group">
                        <label for="title">Title</label>
                        <input id="title" class="form-control" type="text" name="title" value="{{ $listing->title }}">
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" class="form-control"
                                  name="description">{{ $listing->description }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <map-geocode address="{{ $listing->address }}"
                                     house="{{ $listing->house_number }}"
                                     street="{{ $listing->street_name }}"
                                     city="{{ $listing->city }}"
                                     state="{{ $listing->state }}"
                                     postcode="{{ $listing->postcode }}"
                                     country="{{ $listing->country_code }}"
                                     latitude="{{ $listing->latitude }}"
                                     longitude="{{ $listing->longitude }}"/>
                    </div>

                    <listing-dates-input :dates="{{ $listing->date }}"></listing-dates-input>

                    <listing-image-input :images="{{ $listing->image }}" :max_file_size="10"></listing-image-input>

                </form>

                <div class="text-right">
                    <button type="button" id="submit-listing" class="btn btn-primary">Add Listing</button>
                </div>
            </div>
        </div>
    </div>

@endsection

