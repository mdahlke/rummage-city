/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex'
import axios from 'axios';
import 'es6-promise/auto'
import VueLazyload from 'vue-lazyload'

const moment = require('moment');

Vue.use(require('vue-scrollto'))
Vue.use(VueRouter);
Vue.use(Vuex)
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

// import Datetime from 'vue-datetime';
//
// Vue.use(Datetime);
import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';

Vue.component('VueCtkDateTimePicker', VueCtkDateTimePicker);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('listings', require('./views/Listings.vue').default);
Vue.component('listing', require('./views/ListingView.vue').default);
Vue.component('listing-dates-input', require('./components/listings/ListingDatesInput.vue').default);
Vue.component('listing-image-input', require('./components/listings/ListingImageInput.vue').default);
Vue.component('listings-map', require('./components/listings/ListingsMap.vue').default);
Vue.component('listings-list', require('./components/listings/ListingsList.vue').default);
Vue.component('listing-dates', require('./components/listings/ListingDates.vue').default);
Vue.component('listing-images', require('./components/listings/ListingImages.vue').default);
Vue.component('map-geocode', require('./components/Map/Geocode.vue').default);
Vue.component('search-box', require('./components/SearchBox.vue').default);


import App from './views/App';

import router from './config/router';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import store from './config/store/store';

const app = new Vue({
    el: '#app',
    components: {App},
    store,
    router,
    beforeCreate() {
        this.$store.commit('initialise_store');
    },
}).$mount('#app');


require('./bootstrap');
require('./ajax');
require('./listings-map');
require('./saved-listings');
require('./listing-edit');
require('./service-worker');
