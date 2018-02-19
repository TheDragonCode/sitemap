<?php

if (!function_exists('sitemap')) {
    /**
     * @return \Helldar\Sitemap\Services\Sitemap
     */
    function sitemap()
    {
        return new \Helldar\Sitemap\Services\Sitemap();
    }
}
