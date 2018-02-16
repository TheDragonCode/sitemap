<?php

if (!function_exists('sitemap')) {
    /**
     * @return mixed
     */
    function sitemap()
    {
        return new \Helldar\Sitemap\Services\Sitemap();
    }
}
