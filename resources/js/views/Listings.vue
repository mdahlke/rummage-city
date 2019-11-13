<template>
    <div id="listings">

        <ListingsMap ref="listingsMap"
                     @update_listings="update_listings"
                     @update_url="update_url"
                     @set_active_listing="set_active_listing"
                     @scroll_to_active="scroll_to_active"
                     @set_fetching="set_fetching"
        />

        <aside id="listings__sidebar">
            <ListingsList @update_url="update_url"
                          @set_active_listing="set_active_listing"
            />
        </aside>

        <router-view></router-view>

    </div>
</template>

<script>
    import ListingsMap from '../components/listings/ListingsMap.vue';
    import ListingsList from '../components/listings/ListingsList.vue';
    import {updateQueryStringParameter} from '../helpers';
    import {mapState} from 'vuex';

    const VueScrollTo = require('vue-scrollto');

    export default {
        name: 'Listings',
        components: {
            ListingsList,
            ListingsMap
        },
        data() {
            return {
                icon: null,
                loaded: false,
                lat: 43.75171,
                lng: -88.44867,
                zoom: 10,
                bearing: 0,
                pitch: 0,
                searchState: {},
                viewing: false,
                fetching: false,
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
        watch: {
            // call again the method if the route changes
        },
        created() {
            if (this.listings) {
                this.$store.commit('listings', this.listings);
            }

            let searchState = false;

            if (this.search) {
                searchState = this.search;
            } else if (this.$route.query.searchState) {
                searchState = JSON.parse(this.$route.query.searchState);
            } else {
                searchState = this.$store.state.search;
            }
            this.map_data(searchState);
        },
        mounted() {
            if (!this._listings.length) {
                // when the component is mounted then we will be fetching data
                this.fetching = true;
                this.fetch_data();
            }
        },
        computed: {
            localComputed() {
            },
            ...mapState({
                _listings: 'listings',
            })
        },
        methods: {
            set_fetching(is = true) {
                this.fetching = is;
            },
            fetch_data() {
                this.fetching = true;
                const searchState = this.$store.state.search;

                this.map_data(searchState);

                this.$refs.listingsMap.update_map()
                    .update_map_listings();

                this.fetching = false;
            },
            map_data(searchState) {
                if (searchState.bounds && searchState.bounds.lat && searchState.bounds.lng) {
                    this.lat = searchState.bounds.lat;
                    this.lng = searchState.bounds.lng;
                }

                if (searchState.zoom) {
                    this.zoom = searchState.zoom;
                }

                if (searchState.pitch) {
                    this.pitch = searchState.pitch;
                }

                if (searchState.bearing) {
                    this.bearing = searchState.bearing;
                }
            },
            update_listings(listings) {
                this.$store.commit('listings', listings);

                this.scroll_to_active(0);
            },
            update_url(update) {
                const search = _.extend({
                    bounds: null,
                    listing: null,
                    zoom: null,
                    pitch: null,
                    bearing: null
                }, update);

                this.$store.commit('search', search);

                let url = document.URL;
                url = updateQueryStringParameter(url, 'searchState', JSON.stringify(search));
                const searchState = {searchState: JSON.stringify(search)};

                if (this.$route.params.location || false) {
                    this.$router.push({
                        name: 'listings.location',
                        params: {
                            location: (this.$route.params.location)
                        },
                        query: searchState
                    });
                } else {
                    this.$router.push({
                        name: 'listings',
                        query: searchState
                    });
                }
            },
            set_active_listing(listing) {
                this.active_listing = listing;
                this.$store.commit('listing', listing);
                this.scroll_to_active(1);
            },
            scroll_to_active(duration = 500) {
                const el = '#listing-' + this.active_listing.id;

                this.$scrollTo(el, duration, {
                    container: '#listings__sidebar',
                    offset: () => {
                        return -100
                    }
                });
            },
            highlight_on_map(listing) {
                this.$refs.listingsMap.highlight_listing(listing);
            },
            zoom_to_on_map(listing) {
                this.$refs.listingsMap.zoom_to(listing);
            }
        }
    };

</script>


<style lang="scss">
    @import '../../sass/component/listings.scss';
</style>
