<?php

use Helldar\Sitemap\Services\Sitemap;

if (!function_exists('sitemap')) {
    /**
     * @return \Helldar\Sitemap\Services\Sitemap
     */
    function sitemap(): Sitemap
    {
        return app('sitemap');
    }
}

if (!function_exists('is_url')) {
    function is_url(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }
}

