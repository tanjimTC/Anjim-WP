<?php

namespace anjumWpTask\Classes;

use anjumWpTask\Services\PermissionManager;

class AccessControl
{
    public static function checkAccessibility()
    {
        $permissions = PermissionManager::currentUserPermissions();

        if (empty($permissions)) {
           return false;
        };

        if (!PermissionManager::currentUserCan('manage_options') || !in_array('administrator', $permissions)) {
            return false;
        }

        return true;
    }
}
