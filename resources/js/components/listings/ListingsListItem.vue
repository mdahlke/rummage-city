<template>
    <section class="listings-list-item listing"
             :id="'listing-'+ listing.id"
    >

        <div class="listing-wrap">

            <ListingListItemMainInfo :listing="listing"/>

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
                                        <span class="cursor-pointer" @click="emitHighlightOnMap()">
                                            <i class="fas fa-map"></i> Show on Map</span>
                        </li>
                        <li class="list-inline-item listing__action">
                                        <span class="cursor-pointer" @click="emitZoomToOnMap()">
                                            <i class="fas fa-crosshairs"></i> Zoom to</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import ListingListItemMainInfo from './ListingListItemMainInfo';
    import ListingDates from './ListingDates';
    import {listing_mixin} from './shared';
    import {isTrue} from '../../helpers';

    export default {
        name: 'ListingsListItem',
        mixins: [listing_mixin],
        components: {
            ListingDates,
            ListingListItemMainInfo
        },
        props: {
            listing: Object
        },
        computed: {},
        methods: {
            emitHighlightOnMap() {
                console.log(this.listing.title);
                this.$emit('highlight-on-map', this.listing);
            },
            emitZoomToOnMap() {
                this.$emit('zoom-to-on-map', this.listing);
            },
            isSaved(val) {
                return isTrue(val);
            }
        }
    };

</script>

<style lang="scss">
    /*@import '../../../sass/component/listings-list.scss';*/
    /*@import '../../../../node_modules/mapbox-gl/dist/mapbox-gl.css';*/
</style>
