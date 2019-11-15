<template>

    <section class="listings-component">
        <section class="search-bar sub-nav">
            <SearchBox :query="searchedTerm"/>
        </section>

        <div id="listings">

            <ListingsMap ref="listingsMap"
                         :map-attributes="mapAttributes"
                         @update_listings="update_listings"
                         @push_state="push_state"
                         @set_active_listing="set_active_listing"
                         @scroll_to_active="scroll_to_active"
                         @set_fetching="set_fetching"
            />

            <aside id="listings__sidebar">
                <ListingsList @highlight-on-map="highlightOnMap"
                              @zoom-to-on-map="zoomToOnMap"
                              @push_state="push_state"
                              @set_active_listing="set_active_listing"
                />
            </aside>

            <transition>
                <router-view></router-view>
            </transition>
        </div>
    </section>
</template>

<script>
    import SearchBox from '../components/SearchBox.vue';
    import ListingsMap from '../components/listings/ListingsMap.vue';
    import ListingsList from '../components/listings/ListingsList.vue';
    import {updateQueryStringParameter} from '../helpers';
    import {mapState} from 'vuex';

    const VueScrollTo = require('vue-scrollto');

    export default {
        name: 'Listings',
        components: {
            SearchBox,
            ListingsMap,
            ListingsList,
        },
        data() {
            return {
                loaded: false,
                fetching: false,
                viewing: false,
                searchState: {
                    url: {},
                    listing: {},
                    query: {
                        map: {},
                    }
                },
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
        },
        computed: {
            mapAttributes() {
                return this.searchState.query.map;
            },
            searchedTerm() {
                return this.searchState.query.term;
            },
            ...mapState({
                _listings: 'listings',
            })
        },
        watch: {
            // call again the method if the route changes
            $route() {
                this.fetch_data();
                // this.map_data();
                // this.$refs.listingsMap.update_map(false)
            }
        },
        created() {
            if (this.listings) {
                this.$store.commit('listings', this.listings);
            }

            if (this.search) {
                this.search.query = this.search.query;
                this.searchState = this.search;

                this.$store.commit('search', this.searchState);
            } else {
                this.searchState = this.$store.getters.searchState;
            }
        },
        mounted() {
            // this.$el.addEventListener("hightlightOnMap", (e) => {
            //     console.log({e});
            //     this.hightlightOnMap(e)
            // });
            this.$el.addEventListener("zoomToOnMap", (e) => this.zoomToOnMap(e));

            if (!this._listings.length) {
                // when the component is mounted then we will be fetching data
                this.fetch_data();
                this.loaded = true;
            }
        },
        methods: {
            set_fetching(is = true) {
                this.fetching = is;
            },
            fetch_data() {
                let updateUrl = false;
                if (this.$route.name === 'listings') {
                    updateUrl = true;
                }

                this.fetching = true;
                const searchState = this.$store.state.search;

                this.map_data(searchState);

                // this.$refs.listingsMap.update_map()
                //     .update_map_listings(updateUrl);

                this.fetching = false;
            },
            map_data() {

                // let searchState = false;
                //
                // if (this.search && this.search.query) {
                //     searchState = this.search;
                // } else if (this.$route.query.searchState) {
                //     searchState = JSON.parse(this.$route.query.searchState);
                // } else {
                //     searchState = this.$store.state.search;
                // }
                //
                // if (searchState.bounds && searchState.bounds.lat && searchState.bounds.lng) {
                //     this.lat = searchState.bounds.lat;
                //     this.lng = searchState.bounds.lng;
                // }
                //
                // if (searchState.zoom) {
                //     this.zoom = searchState.zoom;
                // }
                //
                // if (searchState.pitch) {
                //     this.pitch = searchState.pitch;
                // }
                //
                // if (searchState.bearing) {
                //     this.bearing = searchState.bearing;
                // }
                //
                // console.log(this.)

            },
            update_listings(listings) {
                this.$store.commit('listings', listings);
                this.scroll_to_active(0);
            },
            push_state() {
                let route;
                // if (this.$route.params.location || false) {
                //     route = {
                //         name: 'listings.location',
                //         params: {
                //             location: (this.$route.params.location)
                //         },
                //     }
                // } else {
                route = {
                    name: 'listings',
                }
                // }

                let queryString = JSON.stringify(this.$store.getters.searchState.query);
                route.query = {searchState: queryString};

                this.$router.push(route);
            },
            set_active_listing(listing) {
                this.active_listing = listing;
                this.$store.commit('listing', listing);
                this.scroll_to_active(1);
            },
            scroll_to_active(duration = 500) {
                const el = '#listings__list';

                this.$scrollTo(el, duration, {
                    container: '#listings__sidebar',
                    offset: () => {
                        return -100
                    }
                });
            },
            highlightOnMap(listing) {
                this.$refs.listingsMap.highlightListing(listing);
            },
            zoomToOnMap(listing) {
                this.$refs.listingsMap.zoomTo(listing);
            }
        }
    };

</script>

<style lang="scss">
    @import '../../sass/component/listings.scss';
</style>
