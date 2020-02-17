const Listings = () => import('../../views/Listings' /* webpackChunkName: 'js/chunks/listings' */);
const ListingView = () => import( '../../views/ListingView' /* webpackChunkName: 'js/chunks/listing-view' */);
const ListingEdit = () => import('../../views/ListingEdit' /* webpackChunkName: 'js/chunks/listing-edit' */);
const ListingEditImages = () => import('../../views/ListingEditImages' /* webpackChunkName: 'js/chunks/listing-edit-images' */);

const listingsMeta = {
    title: 'Listings | Rummage City',
    metaTags: [
        {
            name: 'description',
            content: 'Find rummage sale listings in your area.'
        },
        {
            property: 'og:description',
            content: 'Find rummage sale listings in your area.'
        }
    ]
};

export const listings = {
    path: '/listings',
    name: 'listings',
    beforeRouteUpdate(to, from, next) {
        this.update_map();
        this.init();
        next();
    },
    component: Listings,
    meta: listingsMeta,
    props: {
        query: '',
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
    component: Listings,
    meta: listingsMeta,
    props: {
        query: ''
    }
};

export const listingEdit = {
    path: '/dashboard/listings/:id/edit',
    name: 'listing.edit',
    component: ListingEdit,
    meta: {
        title: 'Edit Listing | Rummage City',
        metaTags: [
            {
                name: 'description',
                content: 'Edit your rummage sale listing.'
            },
            {
                property: 'og:description',
                content: 'Edit your rummage sale listing.'
            }
        ]
    },
    props: {
        isNew: false,
        userListing: {}
    },
};

export const listingEditImage = {
    path: '/dashboard/listings/:id/images/edit',
    name: 'listing.edit.images',
    component: ListingEditImages,
    meta: {
        title: 'Edit Listing Images | Rummage City',
        metaTags: [
            {
                name: 'description',
                content: 'Add/Edit your rummage sale listing images.'
            },
            {
                property: 'og:description',
                content: 'Add/Edit your rummage sale listing images.'
            }
        ]
    }
};

export const listingNew = {
    path: '/dashboard/listings/new',
    name: 'listing.new',
    component: ListingEdit,
    meta: {
        title: 'Add Listing | Rummage City',
        metaTags: [
            {
                name: 'description',
                content: 'Add your rummage sale for others to find.'
            },
            {
                property: 'og:description',
                content: 'Add your rummage sale for others to find.'
            }
        ]
    },
    props: {
        isNew: true,
        userListing: {}
    },
};

export const protectedListingsRoutes = [listingEdit, listingEditImage, listingNew];
const listingsRoutes = [listings, listingsLocation, listingEdit, listingEditImage, listingNew];

export default listingsRoutes;
