<?php

/*
 * This file is part of the Sitemap package.
 *
 * (c) Andrey Helldar <helldar@ai-rus.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helldar\Sitemap;


class SitemapServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/path/to/config/courier.php' => config_path('courier.php'),
        ]);

        $this->loadMigrationsFrom(__DIR__ . '/path/to/migrations');

        $this->loadViewsFrom(__DIR__ . '/path/to/views', 'courier');
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {

    }
}