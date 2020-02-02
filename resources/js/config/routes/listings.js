const Listings = () => import('../../views/Listings' /* webpackChunkName: 'js/chunks/listings' */);
const ListingView = () => import( '../../views/ListingView' /* webpackChunkName: 'js/chunks/listing-view' */);
const ListingEdit = () => import('../../views/ListingEdit' /* webpackChunkName: 'js/chunks/listing-edit' */);
const ListingEditImages = () => import('../../views/ListingEditImages' /* webpackChunkName: 'js/chunks/listing-edit-images' */);

export const listings = {
    path: '/listings',
    name: 'listings',
    beforeRouteUpdate(to, from, next) {
        this.update_map();
        next();
    },
    component: Listings,
    meta: {
        title: 'Listings | Rummage City',
        metaTags: [
            {
                name: 'description',
                content: 'The about page of our example app.'
            },
            {
                property: 'og:description',
                content: 'The about page of our example app.'
            }
        ]
    },
    children: [
        {
            path: ':address?/:id/view',
            name: 'listing.view',
            component: ListingView
        }
    ],
};

export const listingsLocation = {
    path: '/listings/:location',
    name: 'listings.location',
    component: Listings
};

export const listingEdit = {
    path: '/dashboard/listings/:id/edit',
    name: 'listing.edit',
    component: ListingEdit,
    props: {
        isNew: false,
        userListing: {}
    },
};

export const listingEditImage = {
    path: '/dashboard/listings/:id/images/edit',
    name: 'listing.edit.images',
    component: ListingEditImages
};

export const listingNew = {
    path: '/dashboard/listings/new',
    name: 'listing.new',
    component: ListingEdit,
    props: {
        isNew: true,
        userListing: {}
    },
};

export const protectedListingsRoutes = [listingEdit, listingEditImage, listingNew];
const listingsRoutes = [listings, listingsLocation, listingEdit, listingEditImage, listingNew];

export default listingsRoutes;
