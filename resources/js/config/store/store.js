import Vuex from "vuex";
import Vue from 'vue';
import getters from './getters';
import {isTrue} from '../../helpers';

Vue.use(Vuex);


const store = new Vuex.Store({
    state: {
        count: 0,
        listings: [],
        listing: {},
        search: {},
    },
    getters: {
        get_listings: state => {
            return state.listings;
        },
        get_listing_by_id: state => id => {
            return state.listings.find(listing => listing.id === id);
        },
        saved_listings: store => {
            return store.listings.filter(listing => (listing.isSaved == "true"));
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
        listings(state, listings) {
            state.listings = listings;
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
    }
});

store.subscribe((mutation, state) => {
    // Store the state object as a JSON string
    localStorage.setItem('store', JSON.stringify(state));
});

export default store;
