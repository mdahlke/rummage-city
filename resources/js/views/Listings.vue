<template>
    <div class="listings">

        <listings-map ref="listingsMap"
                      @update_visible="update_visible"
                      @update_url="update_url"
                      @set_active_listing="set_active_listing"></listings-map>

        <aside id="listings__sidebar">
            <listings-list @update_url
                           @set_active_listing="set_active_listing">
            </listings-list>
        </aside>
    </div>
</template>

<script>
    import ListingsMap from '../components/Map/ListingsMap.vue';


    import ListingsList from '../components/Map/ListingsList.vue';
    import {setPage, updateQueryStringParameter} from '../helpers';
    import '../../sass/component/listings-map.scss';

    const VueScrollTo = require('vue-scrollto');

    export default {
        name: 'Listings',
        components: {
            'listings-list': ListingsList,
            'listings-map': ListingsMap
        },
        data() {
            return {
                map: {},
                platform: {},
                mapEvents: {},
                data_points: [],
                icon: null,
                listing_ids: [],
                visible_listings: [],
                loaded: false,
                lat: 43.75171,
                lng: -88.44867,
                zoom: 10,
                bearing: 0,
                pitch: 0,
                searchState: {},
                active_listing: {},
            };
        },
        props: {
            listings: Array,
            search: Object,
            appId: {
                type: String,
                default: 'lQPofquBtIF1aAOEarx7'
            },
            appCode: {
                type: String,
                default: '28icBNVvG484yi09NA2c1g'
            },
            mapLat: {
                type: String,
                default: '43.75171'
            },
            mapLng: {
                type: String,
                default: '-88.44867'
            },
            mapZoom: {
                type: Number,
                default: 10
            },
            width: {
                type: String,
                default: '100%'
            },
            height: {
                type: String,
                default: 'calc(100vh - 50px)'
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

            if (this.listings) {
                this.visible_listings = this.listings;
            }

            if (this.search) {
                if (this.search.bounds && this.search.bounds.lat && this.search.bounds.lng) {
                    this.lat = this.search.bounds.lat;
                    this.lng = this.search.bounds.lng;
                }

                if (this.search.zoom) {
                    this.zoom = this.search.zoom;
                }

                if (this.search.pitch) {
                    this.pitch = this.search.pitch;
                }

                if (this.search.bearing) {
                    this.bearing = this.search.bearing;
                }
            }
        },
        mounted() {
            window.addEventListener('popstate', (event) => {
                if (typeof event.state.bounds !== 'undefined') {
                    if (event.state.bounds.lat) {
                        this.lat = event.state.bounds.lat;
                    }
                    if (event.state.bounds.lng) {
                        this.lng = event.state.bounds.lng || this.lng;
                    }
                }
                this.pitch = event.state.pitch;
                this.bearing = event.state.bearing;
                this.zoom = event.state.zoom;

                this.$refs.listingsMap.update_map()
                    .update_map_listings();
            });
        },
        methods: {
            update_visible(listings) {
                this.visible_listings = listings;
            },
            update_url(update) {
                update = _.extend({
                    bounds: null,
                    listing: null,
                    zoom: null,
                    pitch: null,
                    bearing: null
                }, update);

                let url = document.URL;
                url = updateQueryStringParameter(url, 'searchState', JSON.stringify(update));

                setPage(url, 'Listings', update);

            },
            set_active_listing(listing) {
                this.active_listing = listing;
                const el = '#listing-' + listing.id;

                this.$scrollTo(el, 500, {
                    'container': '#listings__sidebar'
                });
            },
            highlight_on_map(listing) {
                this.$refs.listingsMap.highlight_listing(listing);
            }
        }
    };

</script>
