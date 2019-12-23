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

// Add a request interceptor
axios.interceptors.request.use(function (config) {
    // Do something before request is sent
    return config;
}, function (error) {
    // Do something with request error
    return Promise.reject(error);
});

// Add a response interceptor
axios.interceptors.response.use(function (response) {
    // Do something with response data
    return response;
}, function (error) {
    // Do something with response error
    return Promise.reject(error);
});

Vue.component('VueCtkDateTimePicker', () => import('vue-ctk-date-time-picker'/* webpackChunkName: "js/chunks/vue-ctk-date-time-picker" */));

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const App = () => import('./views/App.vue'/* webpackChunkName: "js/chunks/app" */);
Vue.component('listings', () => import('./views/Listings.vue'/* webpackChunkName: "js/chunks/listings" */));
Vue.component('listing', () => import('./views/ListingView.vue'/* webpackChunkName: "js/chunks/listings-view" */));
Vue.component('listings-recent', () => import('./components/listings/ListingsRecent.vue'/* webpackChunkName: "js/chunks/listings-recent" */));
Vue.component('search-box', () => import('./components/SearchBox/SearchBox.vue'/* webpackChunkName: "js/chunks/search-box" */));

Vue.component('passport-clients', require('./components/passport/Clients.vue').default);
Vue.component('passport-authorized-clients', require('./components/passport/AuthorizedClients.vue').default);
Vue.component('passport-personal-access-tokens', require('./components/passport/PersonalAccessTokens.vue').default);

import router from './config/router';
import store from './config/store/store';
import {INITIALISE_STORE} from './config/store/mutations';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
new Vue({
    el: '#app',
    components: {App},
    store,
    router,
    beforeCreate() {
        let storeDataObj;
        let storeData = localStorage.getItem('store');

        if (storeData) {
            storeDataObj = JSON.parse(storeData);
            store.commit(INITIALISE_STORE, storeDataObj);
        }

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
