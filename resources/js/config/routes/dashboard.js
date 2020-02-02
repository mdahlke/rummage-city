const UserDashboard = () => import('../../views/UserDashboard' /* webpackChunkName: 'js/chunks/user-dashboard' */);
const Settings = () => import('../../views/Settings' /* webpackChunkName: 'js/chunks/settings' */);
const MyListings = () => import('../../views/MyListings' /* webpackChunkName: 'js/chunks/my-listings' */);
const SavedListings = () => import('../../views/SavedListings' /* webpackChunkName: 'js/chunks/saved-listings' */);


export const dashboard = {
    path: '/dashboard',
    name: 'dashboard',
    component: UserDashboard,
};

export const myListings = {
    path: '/dashboard/my-listings',
    name: 'myListings',
    component: MyListings,
};

export const savedListings = {
    path: '/dashboard/saved-listings',
    name: 'savedListings',
    component: SavedListings,
};

export const settings = {
    path: '/dashboard/settings',
    name: 'settings',
    component: Settings,
};

const dashboardRoutes = [
    dashboard,
    settings,
    myListings,
    savedListings
];

export default dashboardRoutes;
