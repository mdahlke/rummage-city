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
        get_listings: (state, getters) => {
            if (state.search.query.filter.length) {
                if (state.search.query.filter.indexOf('saved') !== false) {
                    return getters.saved_listings;
                }
            } else {
                return state.listings;
            }
        },
        getAllListings: state => {
            return state.allListings;
        },
        get_listing_by_id: state => id => {
            return state.allListings.find(listing => listing.id === id);
        },
        saved_listings: (store, getters) => {
            const saved = getters.getAllListings.filter(listing => (isTrue(listing.isSaved)));
            return saved;
        },
        saved_listings_count: (store, getters) => {
            return getters.saved_listings.count;
        },
        searchState: store => {
            return store.search;
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
        update_listing: (state, getters) => (listing) => {
            let l = getters.get_listing_by_id(listing.id);
            let index = state.listings.findIndex(el => l.id === el.id);
            let listings = state.listings;

            state.listings[index] = listing;
            listings[index] = listing;

            state.commit('listings', listings);
        },
    },
    actions: {
        filterListings({commit, state, getters}) {
            //     if (!state.search.query.filter.length) {
            //         commit('setListings', getters.getAllListings);
            //     } else if (state.search.query.filter.indexOf('saved') !== false) {
            //         commit('setListings', getters.saved_listings);
            //     }
        }
    }
});

store.subscribe((mutation, state) => {
    // Store the state object as a JSON string
    localStorage.setItem('store', JSON.stringify(state));
});

export default store;
