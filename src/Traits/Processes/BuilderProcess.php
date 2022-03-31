<?php

namespace Helldar\Sitemap\Traits\Processes;

use Carbon\Carbon;
use DragonCode\Core\Xml\Helpers\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use function compact;
use function get_class;
use function route;

trait BuilderProcess
{
    /** @var \DragonCode\Core\Xml\Facades\Xml */
    protected $xml;

    /** @var array */
    protected $builders = [];

    /**
     * Pass the list of model constructors for processing.
     *
     * @param \Illuminate\Database\Eloquent\Builder ...$builders
     *
     * @return \Helldar\Sitemap\Services\Sitemap
     */
    public function builders(Builder ...$builders): self
    {
        $this->builders = (array) $builders;

        return $this;
    }

    /**
     * Read configuration patterns and generate a link to save to the site map.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     */
    protected function processBuilder(Builder $builder)
    {
        $name = get_class($builder->getModel());

        $route      = $this->config($name, 'route', 'index');
        $parameters = $this->config($name, 'route_parameters', ['*']);
        $updated    = $this->config($name, 'lastmod', false);
        $age        = $this->config($name, 'age', 180);
        $changefreq = $this->config($name, 'frequency', 'daily');
        $priority   = $this->config($name, 'priority', 0.5);

        $items = $this->getItems($builder, $updated, $age);

        foreach ($items as $item) {
            $params  = $this->routeParameters($item, $parameters);
            $lastmod = $this->lastmod($item, $updated);
            $loc     = Str::e(route($route, $params));

            $this->xml->addItem(compact('loc', 'lastmod', 'changefreq', 'priority'), 'url');
        }
    }

    /**
     * Reading the configuration for the item and returning the default value if it is missing.
     *
     * @param string $model_name
     * @param string $key
     * @param mixed $default
     * @param bool $ignore_empty
     *
     * @return mixed
     */
    protected function config($model_name, $key, $default = null, $ignore_empty = true)
    {
        $value = Config::get("sitemap.models.{$model_name}.{$key}", null);

        if ($value || ! $ignore_empty) {
            return $value;
        }

        return Config::get("sitemap.{$key}", $default);
    }

    /**
     * Obtaining a selection of elements from the model builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param bool $date_field
     * @param int $age
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function getItems(Builder $builder, $date_field = false, $age = 180)
    {
        if ($age && $date_field) {
            $date = Carbon::now()->addDays(-1 * abs((int) $age));

            return $builder
                ->where($date_field, '>', $date)
                ->get();
        }

        return $builder->get();
    }
}
