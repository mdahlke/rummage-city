<template>
    <section id="listings__map">
        <div id="mapbox"></div>
        <ListingMarkers
                ref="listingMarkers"
                v-if="isMounted"
                :listings="listings"
                :map="map"/>
    </section>
</template>

<script>
    import {mapGetters} from 'vuex';
    import mapbox_config from './mapbox.config.js';
    import geolocation from '../../geolocation';
    import {axiosOne, createElementFromHtml} from '../../helpers';
    import ListingMarkers from './ListingMarkers.vue';
    import MapMarker from './MapMarker.vue';
    import Popup from './Popup.vue';

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
                require: false, // if not passed in we retrieve from the store
            },
        },
        computed: {
            ...mapGetters({
                listings: 'get_listings',
                searchState: 'searchState',
            })
        },
        watch: {
            '$route.params.query.searchState': {
                handler: function (searchState) {
                    // this.update_map();
                },
                deep: true
            }
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

            console.log(this.mapAttributes);
            if (this.mapAttributes.center && Object.keys(this.mapAttributes.center).length !== 0) {
                this.center = this.mapAttributes.center;
            }
            if (this.mapAttributes.zoom) {
                this.zoom = this.mapAttributes.zoom;
            }
            if (this.mapAttributes.pitch) {
                this.pitch = this.mapAttributes.pitch;
            }
            if (this.mapAttributes.bearing) {
                this.bearing = this.mapAttributes.bearing;
            }

            let config = {
                container: 'mapbox',
                style: mapbox_config.style,
                center: this.center,
                zoom: this.zoom,
                pitch: this.pitch,
                bearing: this.bearing,
                // hash: true,
                keyboard: false,
                cluster: true,
            };

            this.map = new mapboxgl.Map(config);

            // Add zoom and rotation controls to the map.
            this.map.addControl(new mapboxgl.NavigationControl());

            this.map.on('dragend', this.update_map_listings);
            this.map.on('zoomend', this.update_map_listings);

            this.isMounted = true;

        },
        methods: {
            update_map_listings(updateUrl = false) {
                let bounds = this.map.getBounds();

                this.$emit('set_fetching', true);

                this.get_listings_in_bounds(bounds).then((results) => {
                    const listings = results.data.data.listings.data;

                    this.$emit('update_listings', listings);

                    this.searchState.query.map.center = this.map.getCenter();
                    this.searchState.query.map.zoom = this.map.getZoom();
                    this.searchState.query.map.pitch = this.map.getPitch();
                    this.searchState.query.map.bearing = this.map.getBearing();
                    this.searchState.query.map.bounds = this.map.getBounds();

                    this.$store.commit('search', this.searchState);

                    if (updateUrl) {
                        this.$emit('push_state');
                    }

                    this.$emit('set_fetching', false);

                });
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
                const query = JSON.stringify(JSON.stringify({
                    sw: bounds._sw,
                    ne: bounds._ne
                }));

                return axiosOne({
                    url: '/graphql',
                    type: 'get',
                    params: {
                        query: `
							query FetchListingsInBounds {
							  listings(bounds: ` + query + `, limit: 150) {
							    data {
							      id
							      title
							      description
							      address
							      latitude
							      longitude
							      isSaved
							      saveUrl
							      removeSavedUrl
							      active_date {
							        start
							        end
							      }
							      image {
							        name
							        url
							      }
							    }
							  }
							}
					`
                    },
                }, 'listings').catch(function (thrown) {
                    if (axios.isCancel(thrown)) {
                        console.log('Request canceled', thrown.message);
                    } else {
                        // handle error
                    }
                });
            },
            center_map_on(lat, lng) {
                this.map.setCenter([lat, lng]);
            },
            update_map() {
                console.log('updating_map');
                this.map.setZoom(this.zoom);
                this.map.setCenter(this.center);
                this.map.setPitch(this.pitch);
                this.map.setBearing(this.bearing);
                //
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
    @import '../../../sass/component/listings-map.scss';
    @import '../../../../node_modules/mapbox-gl/dist/mapbox-gl.css';
</style>
