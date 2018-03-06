<?php

namespace Helldar\Sitemap\Services;

use Carbon\Carbon;
use Helldar\Sitemap\Traits\Helpers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class Sitemap
{
    use Helpers;

    /**
     * @var array
     */
    private $models = [];

    /**
     * @var array
     */
    private $manual = [];

    /**
     * @var \Helldar\Sitemap\Services\Xml
     */
    private $xml;

    /**
     * Sitemap constructor.
     */
    public function __construct()
    {
        $this->xml = Xml::init();
    }

    /**
     * @return \Helldar\Sitemap\Services\MakeItem
     */
    public function makeItem()
    {
        return new MakeItem();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder ...$models_builders
     *
     * @return $this
     */
    public function models(Builder ...$models_builders)
    {
        $this->models = (array) $models_builders;

        return $this;
    }

    /**
     * @param $items
     *
     * @return $this
     */
    public function manual($items)
    {
        $this->manual = (new Manual($items))->get();

        return $this;
    }

    /**
     * @return string
     */
    private function get()
    {
        foreach ($this->models as $model) {
            $this->processBuilder($model);
        }

        foreach ($this->manual as $item) {
            $this->processManual($item);
        }

        return $this->xml->get();
    }

    /**
     * Return response with XML data.
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function show()
    {
        return response($this->get(), 200)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Save data to file.
     *
     * @param null|string $path
     *
     * @return bool|int
     */
    public function save($path = null)
    {
        if (!is_null($path)) {
            $path = config('sitemap.filename', public_path('sitemap.xml'));
        }

        return file_put_contents($path, $this->get());
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     */
    private function processBuilder(Builder $builder)
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
            $loc     = $this->e(route($route, $params, true));

            $this->xml->addItem(compact('loc', 'lastmod', 'changefreq', 'priority'));
        }
    }

    /**
     * @param array $item
     */
    private function processManual($item = [])
    {
        $item = new Collection($item);

        $loc        = $this->e($item->get('loc', config('app.url')));
        $lastmod    = Carbon::parse($item->get('lastmod', Carbon::now()))->toAtomString();
        $changefreq = $item->get('changefreq', config('sitemap.frequency', 'daily'));
        $priority   = (float) $item->get('priority', config('sitemap.priority', 0.5));

        $this->xml->addItem(compact('loc', 'lastmod', 'changefreq', 'priority'));
    }

    /**
     * @param string $model_name
     * @param string $key
     * @param mixed  $default
     * @param bool   $ignore_empty
     *
     * @return mixed
     */
    private function config($model_name, $key, $default = null, $ignore_empty = true)
    {
        $value = config("sitemap.models.{$model_name}.{$key}", null);

        if ($value || !$ignore_empty) {
            return $value;
        }

        return config("sitemap.{$key}", $default);
    }

    /**
     * @param             $item
     * @param string|bool $field
     *
     * @return string
     */
    private function lastmod($item, $field = false)
    {
        if ($field && $value = $item->{$field}) {
            if (is_numeric($value)) {
                return Carbon::createFromTimestamp($value)
                    ->toAtomString();
            }

            return Carbon::parse($value)
                ->toAtomString();
        }

        return Carbon::now()
            ->toAtomString();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param bool                                  $date_field
     * @param int                                   $age
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function getItems(Builder $builder, $date_field = false, $age = 180)
    {
        if ((int) $age && $date_field) {
            $date = Carbon::now()
                ->addDays(-1 * abs((int) $age));

            return $builder
                ->where($date_field, '>', $date)
                ->get();
        }

        return $builder->get();
    }
}
