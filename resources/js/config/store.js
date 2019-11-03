import Vuex from "vuex";
import Vue from 'vue';

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        count: 0,
        listings: [],
        listing: {},
        search: {},
    },
    getters: {
        getListingById: (store) => (id) => {
            return state.listings.find(listing => listing.id === id);
        },
        savedListings: store => {
            return store.listings.filter(listing => listing.saved);
        },
        savedListingsCount: (store, getters) => {
            return getters.savedListings.count;
        }
    },
    mutations: {
        initialiseStore(state) {
            // Check if the ID exists
            if (localStorage.getItem('store')) {
                // Replace the state object with the stored item
                this.replaceState(
                    Object.assign(state, JSON.parse(localStorage.getItem('store')))
                );
            }
        },
        listing(state, listing) {
            state.listing = listing;
        },
        listings(state, listings) {
            state.listings = listings;
        },
        search(state, search) {
            state.search = search;
        }
    }
});

store.subscribe((mutation, state) => {
    // Store the state object as a JSON string
    localStorage.setItem('store', JSON.stringify(state));
});

export default store;
