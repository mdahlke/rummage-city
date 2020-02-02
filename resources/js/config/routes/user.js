const UserDashboard = () => import('../../views/UserDashboard' /* webpackChunkName: 'js/chunks/user-dashboard' */);

export const account = {
    path: '/account',
    name: 'account',
    component: UserDashboard,
};

const userRoutes = [account];

export default userRoutes;
