<?php

namespace anjumWpTask\Services;

class PermissionManager
{
    public static function getUserPermissions($user = null)
    {
        $user = self::resolveUser($user);

        if (!$user) {
            return [];
        }

        return self::extractPermissions($user);
    }

    public static function currentUserPermissions()
    {
        $permissions = self::getUserPermissions(get_current_user_id());

        return $permissions;
    }

    public static function currentUserCan($permission)
    {
        return current_user_can($permission);
    }

    private static function resolveUser($user)
    {
        if (is_numeric($user)) {
            return get_user_by('ID', $user);
        }
    }

    private static function extractPermissions($user)
    {
        $permissions = [];

        if ($user->has_cap('manage_options')) {
            $permissions[] = 'administrator';
        } else {
            $permissions = array_keys($user->allcaps);
        }

        return array_values($permissions);
    }
}
