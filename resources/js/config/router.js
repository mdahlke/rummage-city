import VueRouter from 'vue-router';
require('path-to-regexp');


const Listings = () => import('../views/Listings');
const ListingView = () => import( '../views/ListingView');

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/listings',
            name: 'listings',
            beforeRouteUpdate(to, from, next) {
                this.update_map();
                next();
            },
            component: Listings,
            props: {
                // listings: [],
                // search: {}
            },
            children: [
                // Here we specify that the `ProductImagePopup`
                // component should be rendered as a nested
                // route of the `Product` component.
                {
                    path: ':address?/:id/view',
                    name: 'listing.view',
                    component: ListingView
                }
            ],
        },
        {
            path: '/listings/:location',
            name: 'listings.location',
            component: Listings
        },
    ]
});

export default router;
