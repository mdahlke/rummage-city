<template>
    <section class="listings-list-item listing"
             :id="'listing-'+ listing.id"
             :key="'listing-item-'+listing.id">

        <div class="listing-wrap">

            <div class="listing__main-info"
                 :class="{ 'has-image': listing.image.length}"
                 @click="view_listing( listing)"
            >
                <div class="listing__featured-image" v-if="listing.image.length">
                    <div class="featured-image__blur"
                         :style="'background-image: url('+listing.image[0].url+')'"></div>
                    <div class="featured-image__image"
                         :style="'background-image: url('+listing.image[0].url+')'"></div>
                </div>
                <div class="listing__content">
                    <h1 class="listing__title">{{ listing.title }}</h1>
                    <h3 class="listing__address">{{ listing.address }}</h3>
                </div>
            </div>

            <div class="listing__content">
                <a @click="view_listing( listing)">View</a>

                <listing-dates :dates="listing.active_date"></listing-dates>

                <div class="listing__actions">
                    <ul class="list-inline">
                        <li class="list-inline-item listing__action">
                                    <span class="cursor-pointer" v-if="is_saved(listing.isSaved)"
                                          @click="remove_saved_listing(listing)">
                                            <i class="fas fa-heart"></i> Saved</span>
                            <span class="cursor-pointer" v-else @click="save_listing(listing)">
                                            <i class="far fa-heart"> Save</i></span>
                        </li>
                        <li class="list-inline-item listing__action">
                                        <span class="cursor-pointer" @click="highlight_on_map(listing)">
                                            <i class="fas fa-map"></i> Show on Map</span>
                        </li>
                        <li class="list-inline-item listing__action">
                                        <span class="cursor-pointer" @click="zoom_to_on_map(listing)">
                                            <i class="fas fa-crosshairs"></i> Zoom to</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import {mapGetters, mapMutations} from 'vuex';
    import {listing_mixin} from "./shared";
    import {is_true} from '../../helpers';
    import '../../../sass/component/listings-list.scss';
    import '../../../../node_modules/mapbox-gl/dist/mapbox-gl.css';

    export default {
        name: 'ListingsListItem',
        mixins: [listing_mixin],
        props: {
            listing: Object
        },
        computed: {},
        methods: {
            is_saved(val) {
                return is_true(val);
            }
        }
    };

</script>
