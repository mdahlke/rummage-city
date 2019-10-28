<template>
    <div id="listings__map"></div>
</template>

<script>
    import MapboxPopup from './MapboxPopup.vue';
    import {mapbox_latlng, setPage} from '../../helpers';
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
                visible_listings: []
            };
        },
        props: {
            listings: Array,
            appId: {
                type: String,
                default: 'lQPofquBtIF1aAOEarx7'
            },
            appCode: {
                type: String,
                default: '28icBNVvG484yi09NA2c1g'
            },
            svg: {
                type: String,
                default: null
            }
        },
        created() {
            this.platform = new H.service.Platform({
                'app_id': this.appId,
                'apikey': '7W5ZSgnP_hvci-01R4EbN1_T17e_5x1zVr54MheJxTk'
            });
            if (this.svg) {
                this.icon = new H.map.Icon(this.svg);
            }

        },
        mounted() {
            if (this.listings) {
                this.visible_listings = this.listings;
            }

            console.log('parent longitued', this.$parent.lng);

            mapboxgl.accessToken = 'pk.eyJ1IjoibWRhaGxrZSIsImEiOiJjazI2bGgzNjUwZzlsM2dxaDd2OXgxZW9yIn0.kKYT-PvLDgQeFZWc2MMOAw';
            this.map = new mapboxgl.Map({
                container: 'listings__map',
                style: 'mapbox://styles/mapbox/streets-v11',
                center: {lng: this.$parent.lng, lat: this.$parent.lat},
                zoom: this.$parent.zoom,
                pitch: this.$parent.pitch,
                bearing: this.$parent.bearing,
                // hash: true,
                keyboard: false,
            });

            // Add zoom and rotation controls to the map.
            this.map.addControl(new mapboxgl.NavigationControl());

            /**
             * When the user stops dragging the map we need to get the coordinates
             * to retrieve any new listings that should be visible on the screen
             *
             * @TODO break out the inner logic to allow for zoom in/out
             */
            this.map.on('dragend', this.update_map_listings);
            this.map.on('zoomend', this.update_map_listings);

            let l;
            let popup;
            for (let i in this.listings) {
                popup = null;
                if (this.listings.hasOwnProperty(i)) {
                    l = this.listings[i];

                    if (l.id) {
                        this.listing_ids.push(l.id);
                        popup = this.create_popup(l);
                        this.add_marker(mapbox_latlng(l), popup);
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
                return new mapboxgl.Popup({className: 'listing__popup'})
                    .setHTML("<h1>" + listing.title + "</h1>")
                    .setMaxWidth("300px");
            },
            get_listings_in_bounds(bounds) {
                const query = JSON.stringify(JSON.stringify({
                    sw: bounds._sw,
                    ne: bounds._ne
                }));

                return axios({
                    url: '/search',
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
							      date {
							        start
							        end
							      }
							      image {
							        name
							        path
							      }
							    }
							  }
							}
					`
                    }
                });
            },
            save(listing) {
                console.log('save', listing.isSaved);
                if (!listing.isSaved) {
                    axios.post(listing.saveUrl).then(function (e) {
                        console.log(e);
                        listing.isSaved = true;
                    });
                }
            },
            remove_saved(listing) {
                console.log('remove', listing.isSaved);
                if (listing.isSaved) {
                    axios.post(listing.removeSavedUrl).then((e) => {
                        listing.isSaved = false;
                        console.log(listing);
                    });
                }
            }
        }
    };

</script>
