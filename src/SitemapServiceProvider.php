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

use Illuminate\Support\ServiceProvider as ServiceProvider;

class SitemapServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes(array(
            __DIR__.'/config/sitemap.php' => config_path('sitemap.php'),
        ));

        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app['sitemap'] = $this->app->share(function ($app) {
            return new Factory();
        });
    }
}
