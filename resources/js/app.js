/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import 'es6-promise/auto';
import VueLazyload from 'vue-lazyload';

const moment = require('moment');

Vue.use(require('vue-scrollto'));
Vue.use(VueRouter);
Vue.use(Vuex);
Vue.use(require('vue-moment'), {
    moment
});
Vue.use(VueLazyload);

window.Vue = Vue;
window.axios = require('axios');

// window.axios.defaults.headers.common = {
// 	'X-Requested-With': 'XMLHttpRequest',
// 	'X-CSRF-TOKEN': window.csrf_token
// };

Vue.component('VueCtkDateTimePicker', () => import('vue-ctk-date-time-picker'/* webpackChunkName: "js/chunks/vue-ctk-date-time-picker" */));

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

Vue.component('listings', () => import('./views/Listings.vue'/* webpackChunkName: "js/chunks/listings" */));
Vue.component('listing', () => import('./views/ListingView.vue'/* webpackChunkName: "js/chunks/listings-view" */));
Vue.component('listing-dates-input', () => import('./components/listings/ListingDatesInput.vue'/* webpackChunkName: "js/chunks/listing-dates-input" */));
Vue.component('listing-image-input', () => import('./components/listings/ListingImageInput.vue'/* webpackChunkName: "js/chunks/listing-images-input" */));
Vue.component('map-geocode', () => import('./components/Map/Geocode.vue'/* webpackChunkName: "js/chunks/map-geocode" */));
Vue.component('search-box', () => import('./components/SearchBox/SearchBox.vue'/* webpackChunkName: "js/chunks/search-box" */));


import App from './views/App';

import router from './config/router';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import store from './config/store/store';
import {INITIALISE_STORE} from './config/store/mutations';

new Vue({
    el: '#app',
    components: {App},
    store,
    router,
    beforeCreate() {
        this.$store.commit(INITIALISE_STORE);
    },
}).$mount('#app');


require('./bootstrap');
require('./ajax');
require('./listings-map');
require('./saved-listings');
require('./listing-edit');
require('./service-worker');


// import speedTest from './speed-test';
//
// speedTest(30).then(result => {
//     console.log({result});
// });
