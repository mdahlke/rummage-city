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

// This callback runs before every route change, including on page load.
router.beforeEach((to, from, next) => {
    // This goes through the matched routes from last to first, finding the closest route with a title.
    // eg. if we have /some/deep/nested/route and /some, /deep, and /nested have titles, nested's will be chosen.
    const nearestWithTitle = to.matched.slice().reverse().find(r => r.meta && r.meta.title);

    // Find the nearest route element with meta tags.
    const nearestWithMeta = to.matched.slice().reverse().find(r => r.meta && r.meta.metaTags);
    // const previousNearestWithMeta = from.matched.slice().reverse().find(r => r.meta && r.meta.metaTags);

    // If a route with a title was found, set the document (page) title to that value.
    if (nearestWithTitle) {
        console.log(to);
        let title = nearestWithTitle.meta.title;

        // const regExp = /{{\ (.+)\ }}/;
        // const matches = regExp.exec(nearestWithTitle.meta.title) || [];
        // console.log({matches});
        // let match = false;
        // for (let i = 0; i < matches.length; i++) {
        //     if (to.params[matches[i]] || false) {
        //         match = to.params[matches[i]];
        //         title = title.replace(/\{\{[\s\S]*?\}\}/g, match);
        //     }
        //     console.log(title);
        // }
        // console.log({title});
        document.title = title;
    }

    // Remove any stale meta tags from the document using the key attribute we set below.
    Array.from(document.querySelectorAll('[data-vue-router-controlled]')).map(el => el.parentNode.removeChild(el));

    // Skip rendering meta tags if there are none.
    if (!nearestWithMeta) {
        return next();
    }

    // Turn the meta tag definitions into actual elements in the head.
    nearestWithMeta.meta.metaTags.map(tagDef => {
        const tag = document.createElement('meta');

        Object.keys(tagDef).forEach(key => {
            tag.setAttribute(key, tagDef[key]);
        });

        // We use this to track which meta tags we create, so we don't interfere with other ones.
        tag.setAttribute('data-vue-router-controlled', '');

        return tag;
    })
        // Add the meta tags to the document head.
        .forEach(tag => document.head.appendChild(tag));

    next();
});


export default router;
