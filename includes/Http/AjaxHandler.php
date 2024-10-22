<?php

namespace anjumWpTask\Http;

use anjumWpTask\Classes\AccessControl;
use anjumWpTask\Services\CacheHandler;
use anjumWpTask\Services\Helper;

class AjaxHandler
{
    private $remoteFetchUrl = 'http://localhost:3000/api/data';

    protected $cacheHandler;

    protected $helper;


    public function __construct()
    {
        $this->cacheHandler = new CacheHandler();
        $this->helper = new Helper();
    }

    public function setupAjaxHandlers()
    {
        add_action('wp_ajax_anjum_wp_task_admin_ajax', [$this, 'processRequest']);
    }

    public function processRequest()
    {
        AccessControl::checkAccessibility();

        $route = $this->getRequestedRoute();
        $this->executeAction($route);
    }

    private function getRequestedRoute()
    {
        return sanitize_text_field($_REQUEST['route'] ?? '');
    }

    private function executeAction($route)
    {
        $method = $this->actionMethod($route);
        if ($method) {
            $this->{$method}();
        }
    }

    private function actionMethod($route)
    {
        $routes = [
            'retrieve_settings' => 'retrieveSettings',
            'update_settings' => 'updateSettings',
            'retrieve_data'   => 'retrieveData',
            'clear_cache' => 'clearCache'
        ];

        return $routes[$route] ?? null;
    }

    public function retrieveSettings()
    {
        $taskSettings = maybe_unserialize(get_option('anjum_wp_task_settings', []));
        $settings = $this->helper->organizeSettings($taskSettings);
        wp_send_json_success(['settings' => $settings]);
    }

    public function updateSettings()
    {
        $settingsJson = isset($_REQUEST['settings']) ? sanitize_text_field($_REQUEST['settings']) : '';
        $settings = json_decode(wp_unslash($settingsJson), true);

        try {
            $this->helper->verifySettings($settings);
            $this->updateSettingsData($settings);
            wp_send_json_success(['message' => __("Settings updated successfully!", "anjum-wp-task")], 200);
        } catch (\Exception $exception) {
            $errorMessages = json_decode($exception->getMessage(), true);
            wp_send_json_error(['message' => sanitize_text_field($errorMessages['message'])], 400);
        }
    }

    private function updateSettingsData($settings)
    {
        $organizedSettings = $this->helper->organizeSettings($settings);
        $taskSettings = $this->helper->sanitizeData($organizedSettings);
        update_option('anjum_wp_task_settings', maybe_serialize($taskSettings), "no");
    }

    public function retrieveData()
    {
        try {
            $type = isset($_REQUEST['type']) ? sanitize_text_field($_REQUEST['type']) : 'graph';
            if ($type === 'graph') {
                $graphData = $this->fetchGraphData();
                wp_send_json_success(['graphData' => $graphData]);
            }
            $tableData = $this->fetchTableData();
            wp_send_json_success([
                'tableData' => $tableData,
                'emails' => $this->helper->retrieveEmails()
            ]);
        } catch (\Exception $exception) {
            $error = $exception->getMessage();
            wp_send_json_error(['message' => sanitize_text_field($error)], 400);
        }
    }

    private function fetchGraphData()
    {
        try {
            $allData = $this->fetchApiData();
            $graphData = $allData['graph'] ?? [];
            return $this->helper->configureGraphData($graphData);
        } catch (\Exception $exception) {
            throw new \Exception(sanitize_text_field($exception->getMessage()));
        }
    }

    private function fetchTableData()
    {
        try {
            $allData = $this->fetchApiData();
            $config = maybe_unserialize(get_option('anjum_wp_task_settings', []));
            $settings = $this->helper->organizeSettings($config);
            $tableData = $allData['table']['data']['rows'] ?? [];
            return $this->helper->configureTableData($tableData, $settings);
        } catch (\Exception $exception) {
            throw new \Exception(sanitize_text_field($exception->getMessage()));
        }
    }

    private function fetchApiData()
    {
        try {
            $taskData = $this->cacheHandler->getCache('anjum_wp_task_data');
            if (!$taskData) {
                $taskData = $this->makeHttpRequest();
                $this->cacheHandler->createCache('anjum_wp_task_data', $taskData);
            }
            return $taskData;
        } catch (\Exception $exception) {
            throw new \Exception(sanitize_text_field($exception->getMessage()));
        }
    }

    private function clearCache()
    {
        $this->cacheHandler->deleteCache('anjum_wp_task_data');
        wp_send_json_success(['message' => __("Cache cleared successfully!", "anjum-wp-task")], 200);
    }

    private function makeHttpRequest()
    {
        $request = wp_remote_get(esc_url($this->remoteFetchUrl));

        if (is_wp_error($request)) {
            $message = sanitize_text_field($request->get_error_message());
            throw new \Exception($message);
        }

        $body = json_decode(wp_remote_retrieve_body($request), true);

        if (isset($body['error'])) {
            $error = sanitize_text_field($body['error']['message'] ?? 'Something went wrong, please try again later');
            throw new \Exception($error);
        }

        return $body;
    }
}
