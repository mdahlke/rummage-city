<template>
    <section id="listings__map">
        <div id="mapbox"></div>
        <ListingMarkers
                ref="listingMarkers"
                v-if="isMounted"
                :map="map"
                @addLayer="addLayer"
        />
    </section>
</template>

<script>
    import {mapState, mapGetters, mapActions} from 'vuex';
    import mapbox_config from './mapbox.config.js';
    import geolocation from '../../geolocation';
    import ListingMarkers from './ListingMarkers.vue';
    import MapMarker from './MapMarker.vue';
    import Popup from './Popup.vue';
    import {SEARCH, MAP_STATE} from '../../config/store/mutations';

    const mapboxgl = require('mapbox-gl');

    export default {
        name: 'ListingsMap',
        components: {ListingMarkers, MapMarker, Popup},
        data() {
            return {
                map: {},
                center: {
                    lat: '43.7730',
                    lng: '-88.4471',
                },
                zoom: 10,
                pitch: 0,
                bearing: 0,
                isMounted: false,
            };
        },
        props: {
            mapAttributes: {
                type: Object,
                required: false, // if not passed in we retrieve from the store
                default: () => {
                    return {};
                }
            },
        },
        computed: {
            ...mapGetters({
                searchState: 'getSearch',
                mapState: 'getMapState',
                mapCenter: 'getMapCenter',
            })
        },
        watch: {
            searchState(newValue, oldValue) {
                console.log({newValue});
            },
            // 'mapCenter': {
            //     handler: function (newValue, oldValue) {
            //         console.log('center changed', {newValue, oldValue}, this);
            //         this.map.flyTo({center: this.$store.state.query.map.center, zoom: 9});
            //     },
            //     deep: true
            // },
            // mapCenter: {
            //     handler: function (newValue, oldValue) {
            //         console.log('center changed', {newValue, oldValue}, this);
            //         this.map.flyTo({center: this.$store.state.query.map.center, zoom: 9});
            //     },
            //     deep: true
            // },
            mapCenter: function (newValue, oldValue) {
                if (this.isMounted && typeof this.map !== 'undefined') {
                    console.log('center changed', {newValue, oldValue}, this.map, typeof this.map);
                    this.map.flyTo({
                        center: newValue,
                        zoom: 12
                    });
                }
            },
            // '$route.params.query.searchState': {
            //     handler: function (searchState) {
            //         this.update_map();
            //     },
            //     deep: true
            // },
            // '$store.query.map': {
            //     handler: function (searchState) {
            //         this.update_map();
            //     },
            //     deep: true
            // }
        },
        created() {
            mapboxgl.accessToken = mapbox_config.accessToken;

            geolocation.get().then((r) => {
                // if (!this.listings.length && this.mapMarkers.length) {
                //     this.center_map_on(r.lat, r.lng);
                // }
            });


            geolocation.watch((r) => {
            });
        },
        mounted() {
            let config = {
                container: 'mapbox',
                style: mapbox_config.style,
                center: this.searchState.query.map.center,
                zoom: this.searchState.query.map.zoom,
                pitch: this.searchState.query.map.pitch,
                bearing: this.searchState.query.map.bearing,
                // hash: true,
                keyboard: false,
                cluster: true,
            };

            console.log(this.searchState.query.map.geometry);


            this.map = new mapboxgl.Map(config);
            this.$store.dispatch('getListingsInBounds', this.map.getBounds());
            console.log({config});

            // Add zoom and rotation controls to the map.
            this.map.addControl(new mapboxgl.NavigationControl());

            this.map.on('dragend', this.updateMapListings);
            this.map.on('zoomend', this.updateMapListings);

            this.isMounted = true;
        },
        methods: {
            ...mapActions({
                moveMap: 'moveMap',
            }),
            moveMap() {
                console.log('move it move it');
            },
            addLayer(layer) {
                console.log({layer});
                this.map.on('load', _ => {
                    this.map.addLayer(layer);
                });
            },
            async updateMapListings(updateUrl = false) {
                let bounds = this.map.getBounds();

                this.$emit('set_fetching', true);

                this.searchState.query.map.center = this.map.getCenter();
                this.searchState.query.map.zoom = this.map.getZoom();
                this.searchState.query.map.pitch = this.map.getPitch();
                this.searchState.query.map.bearing = this.map.getBearing();
                this.searchState.query.map.bounds = this.map.getBounds();

                this.$store.commit(MAP_STATE, this.searchState.query.map);

                console.log({bounds});

                await this.$store.dispatch('getListingsInBounds', bounds);

                if (updateUrl) {
                    this.$emit('push_state');
                }

                this.$emit('set_fetching', false);

                // });
                return this;
            },
            add_marker(listing, marker) {
                marker = new mapboxgl.Marker(marker);

                marker.addTo(this.map);

                this.markers.push({
                    id: listing.id,
                    marker
                });
            },
            get_listings_in_bounds(bounds) {

            },
            center_map_on(lat, lng) {
                this.map.setCenter([lat, lng]);
            },
            update_map() {
                console.log('update map');
                console.log(this.map);
                this.map.setZoom(this.zoom);
                this.map.setCenter(this.mapState.center);
                this.map.setPitch(this.pitch);
                this.map.setBearing(this.bearing);

                return this;
            },
            highlightListing(listing) {
                this.$refs.listingMarkers.markers.forEach(marker => {
                    marker.marker.getPopup().remove();

                    if (marker.id === listing.id) {
                        marker.marker.getPopup().addTo(this.map);
                    }
                });
            },
            zoomTo(listing) {
                this.$refs.listingMarkers.markers.forEach(marker => {
                    marker.marker.getPopup().remove();

                    if (marker.id === listing.id) {
                        marker.marker.getPopup().addTo(this.map);
                        this.map.flyTo({
                            center: marker.marker.getPopup()._lngLat,
                            zoom: 18
                        });
                    }
                });
            },
        }
    };

</script>

<style lang="scss">
    @import '../../../../node_modules/mapbox-gl/dist/mapbox-gl.css';
    @import '../../../sass/colors';
    @import '../../../sass/variables';


    #listings__map {

        .mapbox {
            height: 100%;
        }

        .listing__popup {
            max-height: 300px;

            h1 {
                font-size: .8rem;
            }
        }

        .popup__images {
            display: flex;
            flex-wrap: wrap;

            .popup__image {
                flex: 0 0 33.33333%;
                padding: 5px;
            }

            img {
                max-width: 100%;
            }
        }

        #mapbox,
        .mapboxgl-canvas-container,
        .mapboxgl-canvas {
            height: 100% !important;
        }
    }

</style>
