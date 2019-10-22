@extends('layouts.app')

@section('title', 'Listings')

@include('listings.partials.scripts')

@section('main')
    <section class="listings-index">
        <div class="row no-gutters">
            <div class="col-12 col-lg-5">
                <aside class="listings__section listings__wrap">
                    @foreach($listings as $listing)
                        @include('listings.partials.listing', ['listing' => $listing])
                    @endforeach
                </aside>
                {{$listings->links()}}
            </div>
            <div class="col-12 col-lg">
                <section class="listings__section listings__map">
                    <map-listings :listings="{{ @json_encode($listings->all()) }}" />
                </section>
            </div>
        </div>
    </section>
@endsection
