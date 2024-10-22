window.anjumWpTaskBus = new window.anjumWpTask.Vue();
window.anjumWpTask.Vue.mixin({
    methods: {
        applyFilters: window.anjumWpTask.applyFilters,
        addFilter: window.anjumWpTask.addFilter,
        addAction: window.anjumWpTask.addFilter,
        doAction: window.anjumWpTask.doAction,
        $adminGet: window.anjumWpTask.$adminGet,
        $adminPost: window.anjumWpTask.$adminPost,
    }
});

import {routes} from './routes';

const router = new window.anjumWpTask.Router({
    routes: window.anjumWpTask.applyFilters('anjumWpTask_global_vue_routes', routes),
    linkActiveClass: 'active'
});

import App from './AdminApp';

new window.anjumWpTask.Vue({
    el: '#anjum-wp-task_app',
    render: h => h(App),
    router: router
});

