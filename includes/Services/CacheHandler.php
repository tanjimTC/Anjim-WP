<?php

namespace anjumWpTask\Services;

class CacheHandler
{
    public function createCache($transientName, $data)
    {
        $cacheTimeLimit = 3600;
        set_transient($transientName, $data, $cacheTimeLimit);
    }

    public function getCache($transientName)
    {
        $cache = get_transient($transientName);
        if ($cache) {
            return maybe_unserialize($cache);
        }
        return [];
    }

    public function deleteCache($transientName)
    {
        delete_transient($transientName);
    }
}