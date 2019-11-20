import Vuex from "vuex";
import Vue from 'vue';
import getters from './getters';
import {isTrue} from '../../helpers';

Vue.use(Vuex);


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
        getListings: (state, getters) => {
            let listings = state.allListings;

            if (state.search.query.filter.length > 0) {
                if (state.search.query.filter.indexOf('saved') >= 0) {
                    return getters.savedListings;
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
        saved_listings_count: (state, getters) => {
            return getters.savedListings.count;
        },
        searchFilters: state => {
            return state.search.query.filter;
        }
    },
    mutations: {
        set_count(state, count) {
            this.count = count;
        },
        initialise_store(state) {
            // Check if the ID exists
            if (localStorage.getItem('store')) {
                // Replace the state object with the stored item
                this.replaceState(
                    Object.assign(state, JSON.parse(localStorage.getItem('store')))
                );
            }
        },
        setListings(state, listings) {
            state.listings = listings;
        },
        allListings(state, listings) {
            state.allListings = listings;
        },
        set_listing(state, listing) {
            state.listing = listing;
        },
        listing(state, listing) {
            state.listing = listing;
        },
        search(state, search) {
            state.search = search;
        },
    },
    actions: {
        update_listing: ({commit, getters, state}, listing) => {
            let l = getters.get_listing_by_id(listing.id);
            let index = state.allListings.findIndex(el => l.id === el.id);
            let listings = state.listings;

            state.allListings[index] = listing;
            listings[index] = listing;


            commit('allListings', listings);
        },
        filterListings({commit, state, getters}) {
            if (!getters.searchFilters.length) {
                commit('setListings', getters.getAllListings);
            } else if (getters.searchFilters.indexOf('saved') !== false) {
                commit('setListings', getters.saved_listings);
            }
        },
        filterAdd({commit, state}, filter) {
            let search = state.search;

            if (search.query.filter.indexOf(filter) < 0) {
                search.query.filter.push(filter);
                commit('search', search);
            }
        },
        filterRemove({commit, state}, filter) {
            let search = state.search;
            const updatedFilter = _.without(getters.searchFilters, filter);
            search.query.filter = updatedFilter;
            commit('search', search);
        }
    }
});

store.subscribe((mutation, state) => {
    // Store the state object as a JSON string
    localStorage.setItem('store', JSON.stringify(state));
});

export default store;
