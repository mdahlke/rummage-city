<template>
    <section id="listings__map">
        <div id="mapbox"></div>
        <ListingMarkers
                :listings="_listings"
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
            };
        },
        props: {
            listings: Array,
            mapMarkers: {},
            svg: {
                type: String,
                default: null
            }
        },
        created() {
        },
        mounted() {
            mapboxgl.accessToken = mapbox_config.accessToken;

            let config = {
                container: 'mapbox',
                style: mapbox_config.style,
                center: {lng: this.$parent.lng, lat: this.$parent.lat},
                zoom: this.$parent.zoom,
                pitch: this.$parent.pitch,
                bearing: this.$parent.bearing,
                // hash: true,
                keyboard: false,
                cluster: true,
            };

            this.map = new mapboxgl.Map(config);

            // Add zoom and rotation controls to the map.
            this.map.addControl(new mapboxgl.NavigationControl());

            this.map.on('dragend', this.update_map_listings);
            this.map.on('zoomend', this.update_map_listings);

            geolocation.get().then((r) => {
                // if (!this.listings.length && this.mapMarkers.length) {
                //     this.center_map_on(r.lat, r.lng);
                // }
            });

            geolocation.watch((r) => {
            });
        },
        computed: {
            ...mapGetters({
                _listings: 'get_listings',
            })
        },
        methods: {
            update_map_listings() {
                let bounds = this.map.getBounds();

                this.$emit('set_fetching', true);

                this.get_listings_in_bounds(bounds).then((results) => {
                    const listings = results.data.data.listings.data;

                    this.$emit('update_listings', listings);
                    this.$emit('update_url', {
                        bounds: this.map.getCenter(),
                        zoom: this.map.getZoom(),
                        pitch: this.map.getPitch(),
                        bearing: this.map.getBearing()
                    });

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
							  listings(bounds: ` + query + `, limit: 100) {
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
                const latlng = {lon: this.$parent.lng, lat: this.$parent.lat};
                this.map.setZoom(this.$parent.zoom);
                this.map.setCenter(latlng);
                this.map.setPitch(this.$parent.pitch);
                this.map.setBearing(this.$parent.bearing);
                return this;
            },
            highlight_listing(listing) {
                let popup;

                for (let i in this.popups) {
                    if (this.popups.hasOwnProperty(i)) {
                        popup = this.popups[i];
                        popup.popup.remove();

                        if (popup.id === listing.id) {
                            popup.popup.addTo(this.map);
                            this.map.flyTo({
                                center: popup.popup._lngLat
                            });
                        }
                    }
                }
            },
            zoom_to(listing) {
                let popup;

                for (let i in this.popups) {
                    if (this.popups.hasOwnProperty(i)) {
                        popup = this.popups[i];
                        popup.popup.remove();

                        if (popup.id === listing.id) {
                            popup.popup.addTo(this.map);
                            this.map.flyTo({
                                center: popup.popup._lngLat,
                                zoom: 18
                            });
                        }
                    }
                }
            },
        }
    };

</script>

<style lang="scss">
    @import '../../../sass/component/listings-map.scss';
    @import '../../../../node_modules/mapbox-gl/dist/mapbox-gl.css';
</style>
