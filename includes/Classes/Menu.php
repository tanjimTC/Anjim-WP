<?php

namespace anjumWpTask\Classes;

class Menu
{
    public function register()
    {
        add_action('admin_menu', array($this, 'addMenus'));
    }

    public function addMenus()
    {
        $menuPermission = AccessControl::checkAccessibility();
        if (!$menuPermission) {
            return;
        }

        $title = __('Anjum WP Task', 'anjum-wp-task');
        global $submenu;
        add_menu_page(
            $title,
            $title,
            $menuPermission,
            'anjum-wp-task.php',
            array($this, 'enqueueAssets'),
            'dashicons-chart-pie',
            25
        );

        $submenu['anjum-wp-task.php']['table'] = array(
            __('Table', 'anjum-wp-task'),
            $menuPermission,
            'admin.php?page=anjum-wp-task.php#/',
        );
        $submenu['anjum-wp-task.php']['graph'] = array(
            __('Graph', 'anjum-wp-task'),
            $menuPermission,
            'admin.php?page=anjum-wp-task.php#/graph',
        );
        $submenu['anjum-wp-task.php']['settings'] = array(
            __('Settings', 'anjum-wp-task'),
            $menuPermission,
            'admin.php?page=anjum-wp-task.php#/settings',
        );
    }

    public function enqueueAssets()
    {
        do_action('anjum-wp-task/render_admin_app');
        wp_enqueue_script(
            'anjum-wp-task_boot',
            ANJUMWPTASK_URL . 'assets/js/boot.js',
            array('jquery'),
            ANJUMWPTASK_VERSION,
            true
        );

        wp_enqueue_script(
            'anjum-wp-task_js',
            ANJUMWPTASK_URL . 'assets/js/plugin-main-js-file.js',
            array('anjum-wp-task_boot'),
            ANJUMWPTASK_VERSION,
            true
        );

        wp_enqueue_style('anjum-wp-task_admin_css', ANJUMWPTASK_URL . 'assets/css/element.css');

        $anjumWpTaskAdminVars = apply_filters('anjum-wp-task/admin_app_vars', array(
            'assets_url' => ANJUMWPTASK_URL . 'assets/',
            'ajaxurl' => admin_url('admin-ajax.php')
        ));

        wp_localize_script('anjum-wp-task_boot', 'anjumWpTaskAdmin', $anjumWpTaskAdminVars);
    }
}
