import VueRouter from 'vue-router';
import pathToRegexp from 'path-to-regexp';

const Listings = () => import('../views/Listings');
const ListingView = () => import( '../views/ListingView');

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/listings',
            name: 'listings',
            component: Listings,
            props: {listings: []},
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
