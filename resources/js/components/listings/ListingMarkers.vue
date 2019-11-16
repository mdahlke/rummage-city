<template>
    <div style="">
        <div v-for="(listing, index) in _listings">
            <MapMarker :key="'marker-' + listing.id"
                       :listing="listing"
                       :map="map"
                       @add_marker="add_marker"
                       @close_all_popups="close_all_popups"/>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    import MapMarker from './MapMarker.vue';
    import Popup from './Popup.vue';

    export default {
        name: 'ListingMarkers',
        components: {MapMarker, Popup},
        data() {
            return {
                markers: [],
            }
        },
        props: {
            listings: Array,
            map: Object,
        },
        computed: {
            ...mapGetters({
                _listings: 'get_listings',
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
            remove_marker(listing) {
                const marker = this.find(m => m.id = listing.id);
                marker.marker.remove();
            },
            close_all_popups() {
                let marker;
                this.markers.forEach(marker => {
                    marker.marker.getPopup().remove()
                });
            },
            /**
             * @param array markers marker objects
             */
            remove_markers(markers = []) {
                for (let i = markers.length - 1; i >= 0; i--) {
                    markers[i].marker.remove();
                }
            },
            removeAllMarkers() {
                if (this.markers !== null) {
                    for (let i = this.markers.length - 1; i >= 0; i--) {
                        this.markers[i].marker.remove();
                    }
                    this.markers = [];
                    this.listing_ids = [];
                }
            }
        }
    };

</script>
