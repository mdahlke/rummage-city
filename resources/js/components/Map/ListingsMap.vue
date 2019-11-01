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

    console.log(mapbox_config);

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
            };
            console.log({config});
            this.map = new mapboxgl.Map(config);

            // Add zoom and rotation controls to the map.
            this.map.addControl(new mapboxgl.NavigationControl());

            this.map.on('dragend', this.update_map_listings);
            this.map.on('zoomend', this.update_map_listings);

            let l;
            let popup;
            for (let i in this.$parent.listings) {
                popup = null;
                if (this.$parent.listings.hasOwnProperty(i)) {
                    l = this.$parent.listings[i];

                    if (l.id) {
                        this.listing_ids.push(l.id);
                        popup = this.create_popup(l);
                        this.add_marker(mapbox_latlng(l), popup);
                    }
                }
            }
            let m;
            for (let i in this.mapMarkers) {
                popup = null;
                if (this.mapMarkers.hasOwnProperty(i)) {
                    m = this.mapMarkers[i];

                    if (m.id) {
                        this.listing_ids.push(m.id);
                        popup = this.create_popup(m);
                        this.add_marker(mapbox_latlng(m), popup);
                    }
                }
            }
        },
        methods: {
            update_map_listings() {
                let bounds = this.map.getBounds();

                this.get_listings_in_bounds(bounds).then((results) => {
                    console.log({results});
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
                    for (let i in listings) {
                        popup = null;
                        if (listings.hasOwnProperty(i)) {
                            r = listings[i];
                            // laravel is sending this value back as a string
                            // we need to change it to a boolean
                            r.isSaved = (r.isSaved.toLowerCase() === 'true');

                            if (!this.listing_ids.includes(r.id)) {
                                this.listing_ids.push(r.id);

                                popup = this.create_popup(r);
                                this.add_marker(mapbox_latlng(r), popup);
                            }
                        }
                    }
                });
                return this;
            },
            add_marker(coords = null, popup = null) {
                if (!coords.lat || !coords.lng) {
                    return false;
                }

                let marker = new mapboxgl.Marker()
                    .setLngLat({lon: coords.lng, lat: coords.lat})
                    .addTo(this.map);

                if (popup) {
                    marker.setPopup(popup);
                }

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
                console.log(imagesHtml);
                console.log(listing);

                return new mapboxgl.Popup({className: 'listing__popup'})
                    .setHTML(`
                        <h1>` + listing.title + `</h1>
                        ` + imagesHtml + `
                    `)
                    .setMaxWidth("300px");
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
							  listings(bounds: ` + query + `) {
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
                console.log('update_map', {latlng}, this.$parent.zoom);
                this.map.setZoom(this.$parent.zoom);
                this.map.setCenter(latlng);
                this.map.setPitch(this.$parent.pitch);
                this.map.setBearing(this.$parent.bearing);
                return this;
            }
        }
    };

</script>
