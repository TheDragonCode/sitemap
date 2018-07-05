<?php

namespace Helldar\Sitemap;

use Helldar\Sitemap\Services\Sitemap;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/sitemap.php' => config_path('sitemap.php'),
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/sitemap.php', 'sitemap');

        $this->app->singleton('sitemap', Sitemap::class);
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function provides()
    {
        return ['sitemap'];
    }
}
