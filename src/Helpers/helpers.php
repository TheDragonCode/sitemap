<?php

use Helldar\Sitemap\Support\Make\Sitemap;

if (!function_exists('sitemap')) {
    function sitemap()
    {
        return new Sitemap();
    }
}
