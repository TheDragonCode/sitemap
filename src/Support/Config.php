<?php

namespace Helldar\Sitemap\Support;

use Helldar\Sitemap\Contracts\Support\ConfigContract;
use Helldar\Sitemap\Exceptions\Config\UnknownConfigException;
use Illuminate\Config\Repository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config as IlluminateConfig;

class Config implements ConfigContract
{
    /**
     * @param string $key
     * @param null $default
     *
     * @throws UnknownConfigException
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $key      = static::key($key);
        $instance = static::resolve();

        return static::reflection($instance, $key, $default);
    }

    private static function key(string $key): string
    {
        return 'sitemap.' . $key;
    }

    /**
     * @return IlluminateConfig|array|string
     */
    private static function resolve()
    {
        return static::isLaravel()
            ? IlluminateConfig::class
            : static::load();
    }

    private static function load(): array
    {
        $sitemap = require __DIR__ . '/../config/sitemap.php';

        return compact('sitemap');
    }

    private static function isLaravel(): bool
    {
        return class_exists(Repository::class);
    }

    /**
     * @param $instance
     * @param $key
     * @param null $default
     *
     * @throws UnknownConfigException
     *
     * @return mixed
     */
    private static function reflection($instance, $key, $default = null)
    {
        if ($instance instanceof Repository) {
            return call_user_func([$instance, 'get'], $key, $default);
        }

        if (is_array($instance)) {
            return Arr::get($instance, $key, $default);
        }

        throw new UnknownConfigException();
    }
}
