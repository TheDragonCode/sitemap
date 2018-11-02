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
