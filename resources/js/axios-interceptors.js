import axios from 'axios';
// Add a request interceptor
import store from './config/store/store';
import _ from 'lodash';

axios.interceptors.request.use(function (config = {}) {
    config = _.extend({
        params: {},
        headers: {}
    }, config);

    // Do something before request is sent
    config.headers.Authorization = 'Bearer ' + store.state.accessToken;
    config.params._token = window.csrf_token;

    return config;
}, function (error) {
    // Do something with request error
    return Promise.reject(error);
});

// Add a response interceptor
axios.interceptors.response.use(function (res) {
    console.log({res});
    return res;
}, function (error) {
    // Do something with response error
    return Promise.reject(error);
});
