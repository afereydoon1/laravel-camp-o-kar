import auth from '@/middleware/auth';
import guest from '@/middleware/guest';
import admin from '@/middleware/admin';
import NotFound from "@/views/errors/NotFound";
import AccessDenied from "@/views/errors/AccessDenied";

const Dashboard = () => import(/* webpackChunkName: "js/chunks/dashboards" */ '../views/Dashboard/DashboardRoutes.vue');
const Home = () => import(/* webpackChunkName: "js/chunks/home" */ '../views/Home.vue');
const Auth = () => import(/* webpackChunkName: "js/chunks/auth" */ '../views/Auth/AuthRoutes.vue');
const DashboardIndex = () => import(/* webpackChunkName: "js/dashboard" */ '../views/Dashboard/Index.vue');
const DashboardLayout = () => import(/* webpackChunkName: "js/chunks/dashboard" */ '../views/layout/DashboardLayout.vue');
const AdminLayout = () => import(/* webpackChunkName: "js/chunks/admin-layout" */ '../views/layout/AdminLayout.vue');
const AppLayout = () => import(/* webpackChunkName: "js/chunks/app-layout" */ '../views/layout/AppLayout.vue');
const AdminDashboard = () => import(/* webpackChunkName: "js/admin-user" */ '../views/Admin/AdminDashboard.vue');
const AdminUser = () => import(/* webpackChunkName: "js/admin-user" */ '../views/Admin/User/Index.vue');
const AdminUserRoutes = () => import(/* webpackChunkName: "js/admin-users" */ '../views/Admin/User/AdminUserRoutes.vue');
const AdminCategoryRoutes = () => import(/* webpackChunkName: "js/admin-categories" */ '../views/Admin/Category/AdminCategoryRoutes.vue');
const CategoryIndex = () => import(/* webpackChunkName: "js/admin-category" */ '../views/Admin/Category/Index.vue');
const AdminMembershipRoutes = () => import(/* webpackChunkName: "js/admin-memberships" */ '../views/Admin/Membership/AdminMembershipRoutes.vue');
const AdminMembershipIndex = () => import(/* webpackChunkName: "js/admin-membership" */ '../views/Admin/Membership/Index.vue');
const AdminFileRoutes = () => import(/* webpackChunkName: "js/admin-files" */ '../views/Admin/File/AdminFileRoutes.vue');
const AdminFileIndex = () => import(/* webpackChunkName: "js/admin-file" */ '../views/Admin/File/Index.vue');
const AdminCommentIndex = () => import(/* webpackChunkName: "js/admin-comment" */ '../views/Admin/Comment/Index.vue');
const FileRoutes = () => import(/* webpackChunkName: "js/files" */ '../views/Front/File/FileRoutes.vue');
const AdminDiscountRoutes = () => import(/* webpackChunkName: "js/admin-discounts" */ '../views/Admin/Discount/AdminDiscountRoutes.vue');
const AdminDiscountIndex = () => import(/* webpackChunkName: "js/admin-discount" */ '../views/Admin/Discount/Index.vue');
const AdminPaymentIndex = () => import(/* webpackChunkName: "js/admin-payment" */ '../views/Admin/Payment/Index.vue');
const HomeTagged = () => import(/* webpackChunkName: "js/home-tagged" */ '../views/Front/HomeTagged.vue');

export default [
    {
        path: '/',
        component: AppLayout,
        children: [
            {
                path: '/',
                name: 'home',
                component: Home
            },
            {
                path: '/tagged/:slug',
                name: 'home-tagged',
                component: HomeTagged
            },
            {
                path: '/auth/:url',
                name: 'auth',
                component: Auth,
                props: true,
                meta: {
                    middleware: [guest]
                }
            },
            {
                path: '/file/:url',
                name: 'files',
                component: FileRoutes,
                props: true,
                meta: {
                    middleware: [auth]
                },
                children: [
                    {
                        path: ':slug',
                        name: 'file-show'
                    }
                ]
            }
        ]
    },
    {
        path: '/dashboard',
        component: DashboardLayout,
        children: [
            {
                path: '',
                name: 'dashboard',
                component: DashboardIndex,
                props: true
            },
            {
                path: ':url',
                name: 'dashboards',
                component: Dashboard,
                props: true
            }
        ],
        meta: {
            middleware: [auth]
        }
    },
    {
        path: '/admin',
        component: AdminLayout,
        children: [
            {
                path: 'dashboard',
                name: 'admin-dashboard',
                component: AdminDashboard,
                props: true
            },
            {
                path: 'comment',
                name: 'admin-comment',
                component: AdminCommentIndex
            },
            {
                path: 'user',
                name: 'admin-user',
                component: AdminUser
            },
            {
                path: 'user/:url',
                name: 'admin-users',
                component: AdminUserRoutes,
                props: true,
                children: [
                    {
                        path: ':id',
                        name: 'admin-users-edit'
                    }
                ]
            },
            {
                path: 'category',
                name: 'admin-category',
                component: CategoryIndex
            },
            {
                path: 'category/:url',
                name: 'admin-categories',
                component: AdminCategoryRoutes,
                props: true,
                children: [
                    {
                        path: ':slug',
                        name: 'admin-categories-edit'
                    }
                ]
            },
            {
                path: 'membership',
                name: 'admin-membership',
                component: AdminMembershipIndex
            },
            {
                path: 'membership/:url',
                name: 'admin-memberships',
                component: AdminMembershipRoutes,
                props: true,
                children: [
                    {
                        path: ':id',
                        name: 'admin-membership-edit'
                    }
                ]
            },
            {
                path: 'file',
                name: 'admin-file',
                component: AdminFileIndex
            },
            {
                path: 'file/:url',
                name: 'admin-files',
                component: AdminFileRoutes,
                props: true,
                children: [
                    {
                        path: ':slug',
                        name: 'admin-file-edit'
                    }
                ]
            },
            {
                path: 'discount',
                name: 'admin-discount',
                component: AdminDiscountIndex
            },
            {
                path: 'discount/:url',
                name: 'admin-discounts',
                component: AdminDiscountRoutes,
                props: true,
                children: [
                    {
                        path: ':code',
                        name: 'admin-discount-edit'
                    }
                ]
            },
            {
                path: 'payment',
                name: 'admin-payment',
                component: AdminPaymentIndex
            }
        ],
        meta: {
            middleware: [auth, admin]
        }
    },
    {
        path: '/not-found',
        name: 'not-found',
        component: NotFound
    },
    {
        path: '/access-denied',
        name: 'access-denied',
        component: AccessDenied
    },
    {
        path: '*',
        name: 'error404',
        component: NotFound
    }
];
