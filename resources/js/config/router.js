import VueRouter from 'vue-router';
import store from './store/store';
import routes, {protectedRoutes} from './routes';
import Vue from 'vue';

require('path-to-regexp');

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: routes
});

router.beforeEach((to, from, next) => {
    store.dispatch('fetchAccessToken').then(() => {

        if (protectedRoutes.some(route => route.name === to.name)) {

            if (!store.state.accessToken) {
                next('/login');
            } else {
                next();
            }
        } else {
            next();
        }
    });
});

export default router;
