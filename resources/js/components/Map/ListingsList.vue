<template>
    <div class="listings-list">
        <template v-if="$parent.visible_listings.length">
            <section v-for="(listing, index) in $parent.visible_listings"
                     class="listing">
                <h4>{{ listing.title }}</h4>
                <p>{{ listing.address }}</p>

                <h4>Dates:</h4>
                <ul>
                    <li v-for="(date, index) in listing.date">{{ date.start }} - {{ date.end }}</li>
                </ul>
                <div class="listing__actions">
                    <ul class="list-inline">
                        <li class="list-inline-item listing__action">
                            <span class="cursor-pointer" v-if="listing.isSaved" @click="remove_saved(listing)">
                                <i class="fas fa-heart"></i> Saved</span>
                            <span class="cursor-pointer" v-else @click="save(listing)">
                                <i class="far fa-heart"> Save</i></span>
                        </li>
                    </ul>
                </div>
            </section>
        </template>
        <template v-else>
            <section>
                There are no listings in this area.
            </section>
        </template>
    </div>
</template>

<script>
    import MapboxPopup from './MapboxPopup.vue';
    import {mapbox_latlng} from '../../helpers';
    import '../../../sass/component/listings-map.scss';
    import '../../../../node_modules/mapbox-gl/dist/mapbox-gl.css';

    export default {
        name: 'ListingsList',
        // props: {
        //     listings: Array,
        // },
        mounted() {

        },
        methods: {
            save(listing) {
                console.log('save', listing.isSaved);
                if (!listing.isSaved) {
                    axios.post(listing.saveUrl).then(function (e) {
                        listing.isSaved = true;
                    });
                }
            },
            remove_saved(listing) {
                if (listing.isSaved) {
                    axios.post(listing.removeSavedUrl).then((e) => {
                        listing.isSaved = false;
                        console.log(listing);
                    });
                }
            }
        }
    };

</script>
