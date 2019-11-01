<section class="listing">
    <h4>{{ $listing->title }}</h4>
    <p>Hosted by: {{ $listing->user->name }}</p>
    <p>{{ $listing->address }}</p>

    <h4>Dates:</h4>
    <ul>
        @foreach($listing->date as $date)
            <li>{{ $date->start }} - {{ $date->end }}</li>
        @endforeach
    </ul>
    <div class="listing__actions">
        <ul class="list-inline">
            <li class="list-inline-item">
                <a class="ajax-link" href="{{ route('user.listing.saveListing', ['listing' => $listing->id]) }}"
                   data-method="post"><i class="far fa-heart"></i> Save</a>
            </li>
        </ul>
    </div>
    <div class="mapouter">
        <div class="gmap_canvas">
            {{--            <iframe width="600" height="500" id="gmap_canvas" src="https://maps.google.com/maps?q={{ $listing->addressEncoded() }}&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>--}}
        </div>
    </div>
</section>
