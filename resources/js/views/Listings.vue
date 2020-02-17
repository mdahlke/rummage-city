<template>
    <section class="listings-component">
        <section class="search-bar sub-nav">
            <SearchBox :query="searchedTerm"/>
        </section>

        <div id="listings">
            <ListingsMap ref="listingsMap"
                         v-if="loaded"
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

            <transition name="fade" mode="out-in">
                <router-view></router-view>
            </transition>
        </div>

    </section>
</template>

<script>
    import {mapState} from 'vuex';
    import {ALL_LISTINGS, LISTING, SEARCH, SET_LISTINGS} from '../config/store/mutations';

    const SearchBox = () => import('../components/SearchBox/SearchBox.vue'/* webpackChunkName: "js/chunks/search-box" */);
    const ListingsMap = () => import('../components/listings/ListingsMap.vue'/* webpackChunkName: "js/chunks/listings-map" */);
    const ListingsList = () => import('../components/listings/ListingsList.vue'/* webpackChunkName: "js/chunks/listings-list" */);
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
                this.fetchData();
                // this.map_data();
                // this.$refs.listingsMap.update_map(false)
            }
        },
        created() {
            // this.fetchData();
        },
        mounted() {
            if (this.listings) {
                this.update_listings(this.listings);
            }

            if (this.search) {
                this.search.query = this.search.query;
                this.searchState = this.search;

                this.$store.commit(SEARCH, this.searchState);
            } else {
                this.searchState = this.$store.getters.searchState;
            }

            this.$el.addEventListener('zoomToOnMap', e => this.zoomToOnMap(e));

            if (!this._listings.length) {
                // when the component is mounted then we will be fetching data
                // this.fetchData();
                this.loaded = true;
            } else {
                this.loaded = true;
            }
        },
        methods: {
            init(){
                axios.get('/listings', {

                })
            },
            set_fetching(is = true) {
                this.fetching = is;
            },
            fetchData() {
                this.fetching = true;

                // this.$store.dispatch('getListingsInBounds');

                this.fetching = false;
            },
            update_listings() {
                this.scroll_to_active(0);
            },
            push_state() {
                let route;
                route = {
                    name: 'listings',
                };

                let queryString = JSON.stringify(this.$store.state.search.query);
                route.query = {searchState: queryString};

                this.$router.push(route);
            },
            set_active_listing(listing) {
                this.active_listing = listing;
                this.$store.commit(LISTING, listing);
                this.scroll_to_active(1);
            },
            scroll_to_active(duration = 500) {
                const el = '#listings__list';

                this.$scrollTo(el, duration, {
                    container: '#listings__sidebar',
                    offset: () => {
                        return -100;
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

<style lang="scss" scoped>
    @import '../../../node_modules/bootstrap/scss/mixins/breakpoints';
    @import '../../sass/variables';

    #footer {
        display: none;
    }

    #listings {
        display: flex;
        height: calc(100vh - #{$main-header-height});
        overflow: hidden;

        #listings__sidebar {
            display: none;
            flex: 0 0 100%;
            overflow: auto;
        }

        #listings__map {
            flex: 0 0 100%;
        }

        @include media-breakpoint-up(md) {
            #listings__sidebar {
                display: block;
                flex: 0 0 $sidebar-width;
            }
            #listings__map {

                flex: 0 0 calc(100vw - #{$sidebar-width});
            }
        }

        .listing__action {
            cursor: pointer;
        }

    }

</style>
