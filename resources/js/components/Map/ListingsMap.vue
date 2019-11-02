<template>
    <div id="listings__map"></div>
</template>

<script>
    import MapboxPopup from './MapboxPopup.vue';
    import {mapbox_latlng, setPage} from '../../helpers';
    import mapbox_config from './mapbox.config.js';
    import '../../../sass/component/listings-map.scss';
    import '../../../../node_modules/mapbox-gl/dist/mapbox-gl.css';

    const mapboxgl = require('mapbox-gl');

    export default {
        name: 'ListingsMap',
        data() {
            return {
                map: {},
                platform: {},
                mapEvents: {},
                data_points: [],
                icon: null,
                listing_ids: [],
                visible_listings: [],
                markers: [],
                popups: [],
                active_listing_id: null, // only storing id to avoid confusion with parent data
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
            if (this.listings) {
                this.visible_listings = this.listings;
            }

            mapboxgl.accessToken = mapbox_config.accessToken;
            let config = {
                container: 'listings__map',
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

            let l;
            let popup;
            let marker;
            for (let i in this.$parent.listings) {
                popup = null;
                marker = null;
                if (this.$parent.listings.hasOwnProperty(i)) {
                    l = this.$parent.listings[i];

                    if (l.id) {
                        this.listing_ids.push(l.id);
                        popup = this.create_popup(l);
                        marker = this.create_marker(mapbox_latlng(l), popup);
                        this.add_marker(l, marker, popup);
                    }
                }
            }
        },
        methods: {
            update_map_listings() {
                let bounds = this.map.getBounds();

                this.get_listings_in_bounds(bounds).then((results) => {
                    const listings = results.data.data.listings.data;
                    this.visible_listings = listings;

                    this.$emit('update_visible', listings);
                    this.$emit('update_url', {
                        bounds: this.map.getCenter(),
                        zoom: this.map.getZoom(),
                        pitch: this.map.getPitch(),
                        bearing: this.map.getBearing()
                    });

                    let r;
                    let popup;
                    let marker;
                    for (let i in listings) {
                        popup = null;
                        marker = null;
                        if (listings.hasOwnProperty(i)) {
                            r = listings[i];
                            // laravel is sending this value back as a string
                            // we need to change it to a boolean
                            r.isSaved = (r.isSaved.toLowerCase() === 'true');

                            if (!this.listing_ids.includes(r.id)) {
                                this.listing_ids.push(r.id);

                                popup = this.create_popup(r);
                                marker = this.create_marker(mapbox_latlng(r), popup);

                                this.add_marker(r, marker, popup);
                            }
                        }
                    }
                });
                return this;
            },
            add_marker(listing, marker, popup) {
                if (popup) {
                    marker.setPopup(popup);
                }

                marker.addTo(this.map);

                this.markers.push({
                    id: listing.id,
                    marker
                });
            },
            create_marker(coords = null, popup = null) {
                if (!coords.lat || !coords.lng) {
                    return false;
                }

                let marker = new mapboxgl.Marker()
                    .setLngLat({lon: coords.lng, lat: coords.lat});


                return marker;
            },
            add_popup(listing) {
                const popup =
                    new mapboxgl.Popup({className: 'listing__popup'})
                        .setLngLat(mapbox_latlng(listing))
                        .setHTML("<h1>" + listing.title + "</h1>")
                        .setMaxWidth("300px")
                        .addTo(this.map);
            },
            create_popup(listing) {
                let images = _.template(require('./popup_images.html'));
                const imagesHtml = images({listing});
                const popup = new mapboxgl.Popup({className: 'listing__popup'})
                    .setHTML(`
                        <h1>` + listing.title + `</h1>
                        ` + imagesHtml + `
                    `)
                    .setMaxWidth("300px");

                popup.on('open', (e, d) => {
                    this.$emit('set_active_listing', listing);
                    this.active_listing_id = listing.id;
                });
                popup.on('close', (e, d) => {
                    // we only send the update event if the listing id matches the active_listing_id
                    // this allows other markers to be clicked without this event firing
                    if (listing.id === this.active_listing_id) {
                        this.$emit('set_active_listing', {});
                    }
                });

                this.popups.push({
                    id: listing.id,
                    popup
                });

                return popup;
            },
            get_listings_in_bounds(bounds) {
                const query = JSON.stringify(JSON.stringify({
                    sw: bounds._sw,
                    ne: bounds._ne
                }));

                return axios({
                    url: '/graphql',
                    type: 'get',
                    params: {
                        query: `
							query FetchListingsInBounds {
							  listings(bounds: ` + query + `, limit: 100) {
							    data {
							      id
							      title
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
                    }
                });
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
            }
        }
    };

</script>
