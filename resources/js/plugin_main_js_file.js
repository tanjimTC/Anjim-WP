import Vue from './elements';
import Router from 'vue-router';
Vue.use(Router);

import { applyFilters, addFilter, addAction, doAction } from '@wordpress/hooks';
export default class anjumWpTask {
    constructor() {
        this.applyFilters = applyFilters;
        this.addFilter = addFilter;
        this.addAction = addAction;
        this.doAction = doAction;
        this.Vue = Vue;
        this.Router = Router;
    }
    $adminGet(options) {
        options.action = 'anjum_wp_task_admin_ajax';
        return window.jQuery.get(window.anjumWpTaskAdmin.ajaxurl, options);
    }

    $adminPost(options) {
        options.action = 'anjum_wp_task_admin_ajax';
        return window.jQuery.post(window.anjumWpTaskAdmin.ajaxurl, options);
    }
}
