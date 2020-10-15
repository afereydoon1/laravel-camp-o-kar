import Vue from 'vue';
import VueRouter from 'vue-router';
import store from '@/store/store';
import checkAuth from '@/middleware/check-auth';
import middlewarePipeline from "./middlewarePipeline";

Vue.use(VueRouter);

import routes from "./routes";

const router = new VueRouter({
    linkExactActiveClass: 'active',
    mode: 'history',
    routes
});


router.beforeEach((to, from, next) => {
    let matchedMiddleware = [];
    to.matched.some(toRoute => {
        if(toRoute.meta.middleware)
            matchedMiddleware = matchedMiddleware.concat(toRoute.meta.middleware);
    });

    if (matchedMiddleware.length === 0) {
        return next();
    }
    let middleware = [checkAuth, ...matchedMiddleware];

    let context = {
        to, from, next, store
    };

    return middleware[0]({
        ...context, pipe: middlewarePipeline(context, middleware, 1)
    });
});

export default router;

