import Vue from 'vue';
import Router from 'vue-router';
import EventOverview from '../views/event/Overview.vue';

Vue.use(Router);

export const router = new Router({
    mode: 'history',
    base: process.env.BASE_URL,
    routes: [
        {
            path: '/',
            name: 'eventOverview',
            component: EventOverview
        },
        {
            path: '/event/:id',
            name: 'eventDetail',
            component: function () {
                return import('@/views/event/Detail.vue');
            }
        },
        {
            path: '/login',
            name: 'login',
            component: function () {
                return import('../views/Login.vue');
            }
        },
        {
            path: '/users',
            name: 'users',
            component: function () {
                return import('../views/user/Users.vue');
            }
        },
        {
            path: '/users/create',
            name: 'createUser',
            component: function () {
                return import('../views/user/UserCreate.vue');
            }
        },
        {
            path: '/users/:id',
            name: 'user',
            component: function () {
                return import('../views/user/UserEdit.vue');
            }
        }
    ]
});

router.beforeEach((to, from, next) => {
    // redirect to login page if not logged in and trying to access a restricted page
    const publicPages = ['/login'];
    const authRequired = !publicPages.includes(to.path);
    const loggedIn = localStorage.getItem('user');

    if (authRequired && !loggedIn) {
        return next('/login');
    }

    next();
});
