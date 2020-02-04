const UserDashboard = () => import('../../views/UserDashboard' /* webpackChunkName: 'js/chunks/user-dashboard' */);
const Settings = () => import('../../views/Settings' /* webpackChunkName: 'js/chunks/settings' */);
const MyListings = () => import('../../views/MyListings' /* webpackChunkName: 'js/chunks/my-listings' */);
const SavedListings = () => import('../../views/SavedListings' /* webpackChunkName: 'js/chunks/saved-listings' */);


export const dashboard = {
    path: '/dashboard',
    name: 'dashboard',
    component: UserDashboard,
    meta: {
        title: 'Dashboard | Rummage City',
        metaTags: [
            {
                name: 'description',
                content: 'Your Rummage City dashboard.'
            },
        ]
    }
};

export const myListings = {
    path: '/dashboard/my-listings',
    name: 'myListings',
    component: MyListings,
    meta: {
        title: 'My Listings | Rummage City',
        metaTags: [
            {
                name: 'description',
                content: 'View and edit your listings on Rummage City.'
            }
        ]
    }
};

export const savedListings = {
    path: '/dashboard/saved-listings',
    name: 'savedListings',
    component: SavedListings,
    meta: {
        title: 'Saved Listings | Rummage City',
        metaTags: [
            {
                name: 'description',
                content: 'A simple list of all of your saved listings.'
            }
        ]
    }
};

export const settings = {
    path: '/dashboard/settings',
    name: 'settings',
    component: Settings,
    meta: {
        title: 'Settings | Rummage City',
        metaTags: [
            {
                name: 'description',
                content: 'Adjust your preferences for Rummage City.'
            },
        ]
    }
};

const dashboardRoutes = [
    dashboard,
    settings,
    myListings,
    savedListings
];

export default dashboardRoutes;
