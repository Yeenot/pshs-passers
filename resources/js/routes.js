import Vue from 'vue';
import VueRouter from 'vue-router';

import Home from '@/js/pages/Home';
import School from '@/js/pages/School';

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/school',
            name: 'school',
            component: School
        }
    ]
});

export default router;