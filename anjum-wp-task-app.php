<?php

/*
Plugin Name: Anjum WP Task
Plugin URI: https://www.linkedin.com/in/its-tanjim-chowdhury/
Description: This is a plugin for WP task
Version: 1.0.0
Author: Musarrat Anjum Chowdhuy
Author URI: https://www.linkedin.com/in/its-tanjim-chowdhury/
License: A "Slug" license name e.g. GPL2
Text Domain: anjum-wp-task
*/

if (!defined('ABSPATH')) {
    exit;
}
if (!defined('ANJUMWPTASK_VERSION')) {
    define('ANJUMWPTASK_VERSION', '1.0.0');
    define('ANJUMWPTASK_MAIN_FILE', __FILE__);
    define('ANJUMWPTASK_URL', plugin_dir_url(__FILE__));
    define('ANJUMWPTASK_DIR', plugin_dir_path(__FILE__));
    define('ANJUMWPTASK_UPLOAD_DIR', '/anjum-wp-task');

    class anjumWpTask
    {
        public function boot()
        {
            if (is_admin()) {
                $this->adminHooks();
            }
            $this->textDomain();
        }

        public function adminHooks()
        {
            require ANJUMWPTASK_DIR . 'includes/autoload.php';

            //Register Admin menu
            $menu = new \anjumWpTask\Classes\Menu();
            $menu->register();

            // Top Level Ajax Handlers
            $ajaxHandler = new \anjumWpTask\Http\AjaxHandler();
            $ajaxHandler->setupAjaxHandlers();

            add_action('anjum-wp-task/render_admin_app', function () {
                $adminApp = new \anjumWpTask\Classes\AdminApp();
                $adminApp->bootView();
            });
        }

        public function textDomain()
        {
            load_plugin_textdomain('anjum-wp-task', false, basename(dirname(__FILE__)) . '/languages');
        }
    }

    add_action('plugins_loaded', function () {
        (new anjumWpTask())->boot();
    });

    // disabled admin-notice on dashboard
    add_action('admin_init', function () {
        $disablePages = [
            'anjum-wp-task.php',
        ];
        if (isset($_GET['page']) && in_array($_GET['page'], $disablePages)) {
            remove_all_actions('admin_notices');
        }
    });
} else {
    add_action('admin_init', function () {
        deactivate_plugins(plugin_basename(__FILE__));
    });
}
