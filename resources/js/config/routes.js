const Home = () => import('../views/Home' /* webpackChunkName: 'js/chunks/home' */);
const Listings = () => import('../views/Listings' /* webpackChunkName: 'js/chunks/listings' */);
const ListingView = () => import( '../views/ListingView' /* webpackChunkName: 'js/chunks/listing-view' */);
const ListingEdit = () => import('../views/ListingEdit' /* webpackChunkName: 'js/chunks/listing-edit' */);
const UserDashboard = () => import('../views/UserDashboard' /* webpackChunkName: 'js/chunks/user-dashboard' */);
const Login = () => import('../views/Login' /* webpackChunkName: 'js/chunks/login' */);
const Register = () => import('../views/Register' /* webpackChunkName: 'js/chunks/register' */);

const home = {
    path: '/',
    name: 'home',
    component: Home
};
const listings = {
    path: '/listings',
    name: 'listings',
    beforeRouteUpdate(to, from, next) {
        this.update_map();
        next();
    },
    component: Listings,
    children: [
        {
            path: ':address?/:id/view',
            name: 'listing.view',
            component: ListingView
        }
    ],
};
const listingsLocation = {
    path: '/listings/:location',
    name: 'listings.location',
    component: Listings
};
const dashboard = {
    path: '/dashboard',
    name: 'dashboard',
    component: UserDashboard,
};

const listingEdit = {
    path: '/dashboard/listings/:id/edit',
    name: 'listing.edit',
    component: ListingEdit,
    props: {
        isNew: false,
        userListing: {}
    }
};

const listingNew = {
    path: '/dashboard/listings/new',
    name: 'listing.new',
    component: ListingEdit,
    props: {
        isNew: true,
        userListing: {}
    }
};


const account = {
    path: '/account',
    name: 'account',
    component: UserDashboard,
};
const login = {
    path: '/login',
    name: 'login',
    component: Login,
};
const register = {
    path: '/register',
    name: 'register',
    component: Register,
};
const forgotPassword = {
    path: '/forgot-password',
    name: 'password.request',
    component: Login,
};

const routes = [
    home,
    listings,
    listingsLocation,
    dashboard,
    listingEdit,
    listingNew,
    account,
    login,
    register,
    forgotPassword,
];

export const protectedRoutes = [
    dashboard,
    listingEdit,
];

export default routes;
