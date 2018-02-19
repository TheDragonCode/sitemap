<?php

namespace Helldar\Sitemap\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class Sitemap
{
    /**
     * @var array
     */
    private $models = [];

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
     * @return string
     */
    private function get()
    {
        foreach ($this->models as $model) {
            $this->process($model);
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
     * @return bool|int
     */
    public function save()
    {
        $path = config('sitemap.filename', public_path('sitemap.xml'));

        return file_put_contents($path, $this->get());
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     */
    private function process(Builder $builder)
    {
        $name = get_class($builder->getModel());

        $route      = $this->config($name, 'route', 'index');
        $parameters = $this->config($name, 'route_parameters', ['*']);
        $updated    = $this->config($name, 'lastmod', false, false);
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
     * @param \Illuminate\Support\Collection $item
     * @param array                          $fields
     *
     * @return array
     */
    private function routeParameters($item, $fields = [])
    {
        foreach ($fields as $key => &$value) {
            $value = $item->{$value};
        }

        return $fields;
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

    /**
     * Escape HTML special characters in a string.
     *
     * @param $value
     *
     * @return string
     */
    private function e($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
    }
}
