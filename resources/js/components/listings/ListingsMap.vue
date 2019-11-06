<template>
    <div id="listings__map"></div>
</template>

<script>
    import MapboxPopup from './MapboxPopup.vue';
    import {mapbox_latlng, setPage} from '../../helpers';
    import mapbox_config from './mapbox.config.js';
    import moment from 'moment';
    import _ from 'lodash';
    import geolocation from '../../geolocation';
    import {axios_one, create_element_from_html} from '../../helpers';
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

            console.log(this.visible_listings, this.$parent.visible_listings);

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

            this.add_markers_to_map(this.$parent.visible_listings);


            geolocation.get().then((r) => {
                if (!this.listings.length && this.mapMarkers.length) {
                    this.center_map_on(r.lat, r.lng);
                }
            });

            geolocation.watch((r) => {
            });


        },
        methods: {
            update_map_listings() {
                let bounds = this.map.getBounds();

                this.get_listings_in_bounds(bounds).then((results) => {
                    const listings = results.data.data.listings.data;
                    this.visible_listings = listings;


                    this.remove_all_markers();

                    this.$emit('update_visible', listings);
                    this.$emit('update_url', {
                        bounds: this.map.getCenter(),
                        zoom: this.map.getZoom(),
                        pitch: this.map.getPitch(),
                        bearing: this.map.getBearing()
                    });

                    this.add_markers_to_map(listings);

                });
                return this;
            },
            add_markers_to_map(listings) {
                let listing;
                let popup;
                let marker;

                listings.forEach(listing => {
                    popup = null;
                    marker = null;

                    if (listing.id) {
                        this.listing_ids.push(listing.id);
                        popup = this.create_popup(listing);
                        marker = this.create_marker(listing, popup);
                        (listing => {
                            marker.getElement().addEventListener('click', () => {
                                this.$emit('view', listing);
                            });
                        })(listing);

                        this.add_marker(listing, marker, popup);
                    }
                });
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
            create_marker(listing = null, popup = null) {
                const coords = mapbox_latlng(listing);

                if (!coords.lat || !coords.lng) {
                    return false;
                }

                let el;
                let ldate = new moment(listing.active_date[0].start);
                let duration = moment.duration(ldate.diff(moment()));
                let color = '#35495e';
                let strokeColor = '#eaeaea';
                let ellipsisColor = '#fff';
                let ellipsisStrokeColor = '#000';

                if (duration.as('days') < 1) {
                    color = '#42b883';
                    strokeColor = '#000';
                    ellipsisColor = '#35495e';
                    ellipsisStrokeColor = '#fff';
                }
                /**
                 * @see https://editor.method.ac/
                 */
                let html = `
                    <div class="marker">
                        <svg width="39" height="40" xmlns="http://www.w3.org/2000/svg">
                         <title>` + listing.title + `</title>

                         <g>
                          <title>background</title>
                          <rect fill="none" id="canvas_background" height="5.43643" width="5.35052" y="-1" x="-1"/>
                         </g>
                         <g>
                          <title>Layer 1</title>
                          <path stroke="` + strokeColor + `" stroke-width="1" id="svg_1" stroke-linecap="round" stroke-linejoin="round" d="m19.50242,39.5023c2.03471,-19.49403 9.19609,-16.47947 12.47161,-25.56621a12.61174,11.59174 0 1 0 -25.08335,-1.48118a12.40154,11.39853 0 0 0 0.14014,1.41678c1.8596,8.79571 10.93298,5.5876 12.4716,25.63061z" fill="` + color + `" stroke-miterlimit="10"/>
                          <ellipse transform="rotate(0.08227808773517609 19.500000000001684,10.655783653258416) " ry="4.93409" rx="5.20869" id="svg_2" cy="10.65578" cx="19.5" stroke-width="0.5" fill="` + ellipsisColor + `" stroke="` + ellipsisStrokeColor + `"/>
                         </g>
                        </svg>
                    </div>
                `;

                el = create_element_from_html(html);

                return new mapboxgl.Marker(el).setLngLat({lon: coords.lng, lat: coords.lat});
            },
            add_popup(listing) {
                const popup = new mapboxgl.Popup({className: 'listing__popup'})
                    .setLngLat(mapbox_latlng(listing))
                    .setHTML("<h1>" + listing.title + "</h1>")
                    .setMaxWidth("300px")
                    .addTo(this.map);
            },
            create_popup(listing) {
                let images = _.template(require('./popup_images.html'));
                const imagesHtml = images({listing});
                const popup = new mapboxgl.Popup({className: 'listing__popup'})
                    .setHTML(`<strong>` + listing.title + ` </strong>`)
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

                return axios_one({
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
            remove_all_markers() {
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
