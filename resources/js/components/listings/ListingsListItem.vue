<template>
    <section class="listings-list-item listing"
             :id="'listing-'+ listing.id"
    >

        <div class="listing-wrap">

            <div class="listing__main-info"
                 :class="{ 'has-image': listing.image.length}"
                 @click="view_listing( listing)"
            >
                <div v-if="listing.image.length"
                     class="listing__featured-image"
                >
                    <div class="featured-image__blur lazy-background"
                         v-lazy:background-image="listing.image[0].url"
                    ></div>
                    <div class="featured-image__image lazy-background"
                         v-lazy:background-image="listing.image[0].url"
                    ></div>
                </div>
                <div class="listing__content">
                    <h1 class="listing__title">{{ listing.title }}</h1>
                    <h3 class="listing__address">{{ listing.address }}</h3>
                </div>
            </div>

            <div class="listing__content">
                <!--                <a @click="view_listing( listing)">View</a>-->

                <ListingDates :dates="listing.active_date"/>

                <div class="listing__actions">
                    <ul class="list-inline">
                        <li class="list-inline-item listing__action">
                                    <span class="cursor-pointer" v-if="isSaved(listing.isSaved)"
                                          @click="remove_saved_listing(listing)">
                                            <i class="fas fa-star"></i> Saved</span>
                            <span class="cursor-pointer" v-else @click="save_listing(listing)">
                                            <i class="far fa-star"> Save</i></span>
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
    import ListingDates from "./ListingDates";
    import {listing_mixin} from "./shared";
    import {isTrue} from '../../helpers';

    export default {
        name: 'ListingsListItem',
        mixins: [listing_mixin],
        components: {ListingDates},
        props: {
            listing: Object
        },
        computed: {},
        methods: {
            isSaved(val) {
                return isTrue(val);
            }
        }
    };

</script>

<style lang="scss">
    @import '../../../sass/component/listings-list.scss';
    @import '../../../../node_modules/mapbox-gl/dist/mapbox-gl.css';
</style>
