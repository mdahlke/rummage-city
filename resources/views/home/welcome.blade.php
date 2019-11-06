@extends('layouts.full-width')

@section('content')
<div class="flex-center position-ref full-height">

    <div class="content">
        <div class="title m-b-md text-primary">
            Rummage City
        </div>
            <search-box route="{{ route('listings.browse') }}" query="{{ request('q') }}" :show-filters="false"></search-box>
    </div>
</div>
@endsection
