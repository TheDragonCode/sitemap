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

use Helldar\Sitemap\Console\SitemapCommand;
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
        $this->publishes([
            __DIR__ . '/config/sitemap.php' => config_path('sitemap.php'),
        ]);

        $this->loadMigrationsFrom(__DIR__ . '/migrations/');

        if ($this->app->runningInConsole()) {
            $this->commands([
                SitemapCommand::class,
            ]);
        }
    }
}
