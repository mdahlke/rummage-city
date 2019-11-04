<template>
    <div class="geocode-input">
        <input type="hidden"
               name="latitude"
               :value="lat">
        <input type="hidden"
               name="longitude"
               :value="lng">
        <input type="hidden"
               name="address"
               :value="location_address">
        <input type="hidden"
               name="house_number"
               :value="location_house">
        <input type="hidden"
               name="street_name"
               :value="location_street">
        <input type="hidden"
               name="city"
               :value="location_city">
        <input type="hidden"
               name="state"
               :value="location_state">
        <input type="hidden"
               name="postcode"
               :value="location_postcode">
        <input type="hidden"
               name="country_code"
               :value="location_country">
        <div id="listings__map"></div>
    </div>
</template>
<script>
    import mapbox_config from './../listings/mapbox.config.js';
    import '../../../sass/component/listings-map.scss';
    import '../../../../node_modules/mapbox-gl/dist/mapbox-gl.css';
    import '../../../../node_modules/@mapbox/mapbox-gl-geocoder/lib/mapbox-gl-geocoder.css';

    const mapboxgl = require('mapbox-gl');
    const MapboxGeocoder = require('@mapbox/mapbox-gl-geocoder');


    export default {
        name: 'GeocodeMap',
        data() {
            return {
                map: {},
                marker: {},
                location_address: '',
                location_house: '',
                location_street: '',
                location_city: '',
                location_state: '',
                location_postcode: '',
                location_country: '',
                lat: '37.7397',
                lng: '-121.4252',
                zoom: 10,
                icon: null
            };
        },
        props: {
            address: String,
            house: String,
            street: String,
            city: String,
            state: String,
            postcode: String,
            country: String,
            latitude: String,
            longitude: String,
            mapZoom: Number,
            svg: {
                type: String,
                default: null
            }
        },
        created() {
            if (this.latitude) {
                this.lat = this.latitude;
            }
            if (this.longitude) {
                this.lng = this.longitude;
            }
            if (this.address) {
                this.location_address = this.address;
            }
            if (this.city) {
                this.location_city = this.city;
            }
            if (this.street) {
                this.location_street = this.street;
            }
            if (this.house) {
                this.location_house = this.house;
            }
            if (this.state) {
                this.location_state = this.state;
            }
            if (this.country) {
                this.location_country = this.country;
            }
            if (this.postcode) {
                this.location_postcode = this.postcode;
            }

        },
        mounted() {
            if (this.listings) {
                this.visible_listings = this.listings;
            }

            mapboxgl.accessToken = mapbox_config.accessToken;
            let config = {
                container: 'listings__map',
                style: mapbox_config.style,
                center: {lng: this.lng, lat: this.lat},
                zoom: this.zoom,
                hash: true,
                draggable: true,
            };
            this.map = new mapboxgl.Map(config);

            // Add zoom and rotation controls to the map.
            this.geocoder = new MapboxGeocoder({
                accessToken: mapboxgl.accessToken,
                placeholder: this.address,
                mapboxgl: mapboxgl,
                marker: false,
            });
            this.map.addControl(new mapboxgl.NavigationControl());

            this.geocoder.on('results', this.geocode_results);
            this.geocoder.on('result', this.geocode_result);

            this.map.addControl(this.geocoder);

            this.add_marker(this.lat, this.lng);

        },
        methods: {
            add_marker(lat = null, lon = null) {
                console.log({lat, lon});

                if (!lat || !lon) {
                    return false;
                }

                this.marker = new mapboxgl.Marker({
                    draggable: true,
                })
                    .setLngLat({lon, lat})
                    .addTo(this.map);

                this.marker.on('dragend', this.update_lat_lng);

                return this.marker;
            },

            // Define a callback function to process the geocoding response:
            geocode(address) {
                // Call the geocode method with the geocoding parameters,
                // the callback and an error callback function (called if a
                // communication error occurs):
                this.geocoder.geocode({searchText: address.target.value}, (result) => {
                    let locations = result.Response.View[0].Result,
                        position,
                        marker,
                        i;
                    // Add a marker for each location found
                    for (i = 0; i < locations.length; i++) {
                        position = {
                            lat: locations[i].Location.DisplayPosition.Latitude,
                            lng: locations[i].Location.DisplayPosition.Longitude
                        };
                        this.lat = position.lat;
                        this.lng = position.lng;
                        this.add_marker(position);
                    }
                }, function (e) {
                    console.log(e);
                });
            },
            /**
             * Results from actively searching
             * @param results
             */
            geocode_results(results) {
                console.log({results});
            },
            /**
             * Result when a search result is chosen
             * @param result
             */
            geocode_result(result) {
                const r = result.result;
                this.location_address = r.place_name;
                this.location_house = r.address;
                this.location_street = r.text;
                this.location_city = r.context[1].text;
                this.location_state = r.context[2].text;
                this.location_postcode = r.context[0].text;
                this.location_country = r.context[3].short_code;
                this.lat = r.center[1];
                this.lng = r.center[0];
                this.marker.setLngLat([this.lng, this.lat]);
            },
            update_lat_lng() {
                const latlng = this.marker.getLngLat();
                console.log(latlng);
                this.lat = latlng.lat;
                this.lng = latlng.lng;
            }
        }
    };

</script>

<style scoped>
    #listings__map {
        height: 300px;
    }
</style>
