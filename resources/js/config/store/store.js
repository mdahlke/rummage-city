import Vuex from 'vuex';
import Vue from 'vue';
import axios from 'axios';
import _ from 'lodash';
import {isTrue, isListingToday, isListingThisWeekend, axiosOne} from '../../helpers';
import {INITIALISE_STORE, SET_LISTINGS, ALL_LISTINGS, SET_LISTING, LISTING, SEARCH} from './mutations';

Vue.use(Vuex);

Object.defineProperty(Array.prototype, 'unique', {
    enumerable: false,
    configurable: false,
    writable: false,
    value: function () {
        var a = this.concat();
        for (var i = 0; i < a.length; ++i) {
            for (var j = i + 1; j < a.length; ++j) {
                if (a[i] === a[j])
                    a.splice(j--, 1);
            }
        }

        return a;
    }
});


const store = new Vuex.Store({
    // namespaced: false,
    state: {
        count: 0,
        listings: [],
        allListings: [],
        savedListings: [],
        listing: {},
        search: {
            query: {
                filter: [],
                listing: {},
                map: {},
                term: '',
            },
            url: ''
        },
    },
    getters: {
        getListings: (state) => {
            let listings = state.allListings;

            if (state.search.query.filter.length > 0) {
                let filterListings = listings;
                let savedListings = [];
                let todayListings = [];
                let weekendListings = [];
                let today = false;
                let weekend = false;

                if (state.search.query.filter.includes('saved')) {
                    savedListings = listings.filter(listing => (isTrue(listing.isSaved)));
                    filterListings = savedListings;
                }
                if (state.search.query.filter.includes('today')) {
                    today = true;
                    todayListings = filterListings.filter(listing => isListingToday(...listing.active_date));
                }
                if (state.search.query.filter.includes('weekend')) {
                    weekend = true;
                    weekendListings = filterListings.filter(listing => isListingThisWeekend(...listing.active_date));
                }

                if (today || weekend) {
                    listings = todayListings.concat(weekendListings).unique();
                } else {
                    listings = filterListings;
                }
            }

            return listings;
        },
        getAllListings: state => {
            return state.allListings;
        },
        get_listing_by_id: state => id => {
            return state.allListings.find(listing => listing.id === id);
        },
        savedListings: (state, getters) => {
            return getters.getAllListings.filter(listing => (isTrue(listing.isSaved)));
        },
        listingsToday: (state, getters) => {
            return getters.getAllListings.filter(listing => isListingToday(...listing.active_date));
        },
        saved_listings_count: (state, getters) => {
            return getters.savedListings.count;
        },
        searchFilters: state => {
            return state.search.query.filter;
        }
    },
    mutations: {
        [INITIALISE_STORE](state) {
            // Check if the ID exists
            if (localStorage.getItem('store')) {
                // Replace the state object with the stored item
                this.replaceState(
                    Object.assign(state, JSON.parse(localStorage.getItem('store')))
                );
            }
        },
        [SET_LISTINGS](state, listings) {
            state.listings = listings;
        },
        [ALL_LISTINGS](state, listings) {
            state.allListings = listings;
        },
        [SET_LISTING](state, listing) {
            state.listing = listing;
        },
        [LISTING](state, listing) {
            state.listing = listing;
        },
        [SEARCH](state, search) {
            state.search = search;
        },
    },
    actions: {
        getListingsInBounds: ({commit}, bounds) => {
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
							  listings(bounds: ` + query + `, limit: 150) {
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
            }).then(results => {
                const listings = results.data.data.listings.data || false;

                console.log({listings});
                if (listings) {
                    commit(ALL_LISTINGS, listings);
                    commit(SET_LISTINGS, listings);
                }
            });
        },
        update_listing: ({commit, getters, state}, listing) => {
            let l = getters.get_listing_by_id(listing.id);
            let index = state.allListings.findIndex(el => l.id === el.id);
            let listings = state.listings;

            state.allListings[index] = listing;
            listings[index] = listing;


            commit(ALL_LISTINGS, listings);
        },
        filterListings({commit, getters}) {
            if (!getters.searchFilters.length) {
                commit(SET_LISTINGS, getters.getAllListings);
            } else if (getters.searchFilters.indexOf('saved') !== false) {
                commit(SET_LISTINGS, getters.saved_listings);
            }
        },
        filterAdd({commit, state}, filter) {
            let search = state.search;

            if (search.query.filter.indexOf(filter) < 0) {
                search.query.filter.push(filter);
                commit(SEARCH, search);
            }
        },
        filterRemove({commit, state, getters}, filter) {
            let search = state.search;
            const updatedFilter = _.without(getters.searchFilters, filter);
            search.query.filter = updatedFilter;
            commit(SEARCH, search);
        }
    }
});

store.subscribe((mutation, state) => {
    // Store the state object as a JSON string
    localStorage.setItem('store', JSON.stringify(state));
});

export default store;
