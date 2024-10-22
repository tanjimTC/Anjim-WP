<?php

namespace anjumWpTask\Services;

class Helper
{
    // Generate random colors for charts
    public static function generateRandomColors($count)
    {
        $backgroundColors = [];

        for ($i = 0; $i < $count; $i++) {
            $colors = '#' . substr(md5(mt_rand()), 0, 6);
            $backgroundColors[]  = $colors;
        }

        return $backgroundColors;
    }

    // validate settings
    public static function verifySettings($settings)
    {
        $errors = [];
        $existingEmails = static::retrieveEmails();

        if (!isset($settings['emails']) || !is_array($settings['emails'])) {
            $errors['message'] = 'Email addresses are required';
        } else {
            $uniqueEmails = array_unique($settings['emails']);
            if (count($uniqueEmails) !== count($settings['emails'])) {
                $errors['message'] = 'Duplicate email addresses Found!';
            } else {
                foreach ($settings['emails'] as $email) {
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $errors['message'] = 'Invalid email address';
                        break;
                    }
                }
            }
        }

        if (!isset($settings['dateFormat']) || empty($settings['dateFormat'])) {
            $errors['message'] = 'Date format field is required!';
        }


        if (!isset($settings['rowsToShow']) || empty($settings['rowsToShow'])) {
            $errors['message'] = 'Rows number field is required!';
        } elseif (!is_numeric($settings['rowsToShow'])) {
            $errors['message'] = 'Rows number must be numeric!';
        }

        if (!empty($errors)) {
            throw new \Exception(json_encode($errors));
        }

    }

    // Get formatted settings with defaults
    public static function organizeSettings($settings)
    {
        $user = wp_get_current_user();

        $defaultEmail = isset($user->user_email) ? [$user->user_email] : [];

        $settings = [
            'rowsToShow'   => isset($settings['rowsToShow']) ? (int) sanitize_text_field($settings['rowsToShow']) : 5,
            'dateFormat'   => isset($settings['dateFormat']) ? sanitize_text_field($settings['dateFormat']) : 'human_readable',
            'emails'       => isset($settings['emails']) ? array_map('sanitize_email', $settings['emails']) : $defaultEmail,
        ];

        return $settings;
    }

    // sanitize data
    public static function sanitizeData ($data)
    {
        return [
            'rowsToShow' => sanitize_text_field($data['rowsToShow']),
            'dateFormat' => sanitize_text_field($data['dateFormat']),
            'emails'     => array_map('sanitize_email', $data['emails']),
        ];

    }

    public static function retrieveEmails()
    {
        $taskSettings = maybe_unserialize(get_option('anjum_wp_task_settings', []));
        $settings = static::organizeSettings($taskSettings);
        return isset($settings['emails']) ? $settings['emails'] : [];
    }

    // Format graph data
    public static function configureGraphData($graphData)
    {
        $labels = [];
        $values = [];

        foreach ($graphData as $data) {
            $labels[] = date('d F Y', sanitize_text_field($data['date']));
            $values[] = sanitize_text_field($data['value']);
        }

        return [
            'labels' => $labels,
            'values' => $values,
            'colors' => static::generateRandomColors(count($values))
        ];
    }

    // Format table data based on settings
    public static function configureTableData($tableData, $settings)
    {
        $formattedTableData = [];
        $rowsToDisplay = isset($settings['rowsToShow']) ? (int) $settings['rowsToShow'] : 5;

        foreach ($tableData as $key => $data) {
            $formattedTableData[$key] = $data;
            if ($settings['dateFormat'] !== 'unix') {
                $formattedTableData[$key]['date'] = date('d M Y', sanitize_text_field($data['date']));
            }

            if (count($formattedTableData) === $rowsToDisplay) {
                break;
            }
        }

        return $formattedTableData;
    }
}
