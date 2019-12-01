<template>
    <div>

        <div v-for="(listing, index) in listings">
            <MapMarker :key="'marker-' + listing.id"
                       :listing="listing"
                       :map="map"
                       @add_marker="add_marker"
                       @removeMarker="removeMarker"
                       @close_all_popups="close_all_popups"
            />
        </div>
    </div>
</template>

<script>
    import {mapGetters, mapActions} from 'vuex';
    import MapMarker from './MapMarker.vue';
    import Popup from './Popup.vue';

    export default {
        name: 'ListingMarkers',
        components: {MapMarker, Popup},
        data() {
            return {
                markers: [],
            };
        },
        props: {
            map: Object,
        },
        computed: {
            ...mapGetters({
                listings: 'getListings',
            })
        },
        watch: {
            listings(newListings, oldListings) {
                this.redrawMarkers();
            }
        },
        methods: {
            add_marker(listing, marker) {
                marker.addTo(this.map);
                this.markers.push({
                    id: listing.id,
                    marker
                });
            },
            removeMarker(listing) {
                const marker = this.markers.find(m => m.id = listing.id);
                if (typeof marker !== 'undefined') {
                    marker.marker.remove();
                }
            },
            redrawMarkers() {
                this.removeAllMarkers();
            },
            close_all_popups() {
                let marker;
                this.markers.forEach(marker => {
                    marker.marker.getPopup().remove();
                });
            },
            /**
             * @param array markers marker objects
             */
            removeMarkers(markers = []) {
                markers.forEach(marker => {
                    marker.marker.remove();
                });

            },
            removeAllMarkers() {
                if (this.markers !== null) {
                    this.markers.forEach(marker => {
                        marker.marker.remove();
                    });

                    this.markers = [];
                    this.listing_ids = [];
                }
            }
        }
    };

</script>
