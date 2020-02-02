const Login = () => import('../../views/Login' /* webpackChunkName: 'js/chunks/login' */);
const Register = () => import('../../views/Register' /* webpackChunkName: 'js/chunks/register' */);

export const login = {
    path: '/login',
    name: 'login',
    component: Login,
};

export const register = {
    path: '/register',
    name: 'register',
    component: Register,
};

export const forgotPassword = {
    path: '/forgot-password',
    name: 'password.request',
    component: Login,
};

const authRoutes = [login,
    register,
    forgotPassword,
];

export default authRoutes;
