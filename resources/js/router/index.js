import Vue from 'vue';
import VueRouter from 'vue-router';
import Task from '../vue/views/Task.vue';

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        name: 'Task',
        component: Task,
    },
];

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes
});

export default router;
