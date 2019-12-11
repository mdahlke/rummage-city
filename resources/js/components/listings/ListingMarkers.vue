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
                layer: {},
                features: [],
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

                    // mapbox doesn't do a great job of removing the markers from the DOM
                    // so we do it here just to make sure it is gone
                    document.querySelectorAll('#marker-' + marker.id).forEach(el => {
                        el.parentNode.removeChild(el);
                    });

                }
            },
            redrawMarkers() {
                this.removeAllMarkers();
            },
            close_all_popups() {
                this.markers.forEach(marker => marker.marker.getPopup().remove());
            },
            /**
             * @param array markers marker objects
             */
            removeMarkers(markers = []) {
                markers.forEach(marker => marker.marker.remove());

                document.querySelectorAll('.marker').forEach(el => {
                    el.parentNode.removeChild(el);
                });

                this.markers = [];
            },
            removeAllMarkers() {
                if (this.markers !== null) {
                    this.removeMarkers(this.markers);
                }
            }
        }
    };

</script>
