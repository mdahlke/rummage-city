import _ from 'lodash';
import dashboardRoutes from './routes/dashboard';
import listingsRoutes, {protectedListingsRoutes} from './routes/listings';
import authRoutes from './routes/auth';
import userRoutes from './routes/user';

const Home = () => import('../views/Home' /* webpackChunkName: 'js/chunks/home' */);

const home = {
    path: '/',
    name: 'home',
    component: Home,
    meta: {
        title: 'Rummage City',
        metaTags: [
            {
                name: 'description',
                content: 'Rummage City is your all in one rummage sale finder that is available to you everywhere.'
            },
            {
                property: 'og:description',
                content: 'Rummage City is your all in one rummage sale finder that is available to you everywhere.'
            }
        ]
    },
};

const routes = _.concat(
    [home],
    ...dashboardRoutes,
    ...listingsRoutes,
    ...authRoutes,
    ...userRoutes
);

export const protectedRoutes = _.concat(
    ...protectedListingsRoutes,
    ...dashboardRoutes,
    ...userRoutes,
);

export default routes;
