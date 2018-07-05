<?php

namespace Helldar\Sitemap\Services;

use Carbon\Carbon;
use Helldar\Sitemap\Exceptions\MethodNotExists;
use Helldar\Sitemap\Traits\Helpers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class Sitemap
{
    use Helpers;

    /**
     * @var array
     */
    private $builders = [];

    /**
     * @var array
     */
    private $manuals = [];

    /**
     * @var \Illuminate\Support\Collection
     */
    private $sitemaps;

    /**
     * @var \Helldar\Sitemap\Services\Xml
     */
    private $xml;

    /**
     * @var int
     */
    private $index;

    /**
     * Sitemap constructor.
     */
    public function __construct()
    {
        $this->xml      = Xml::init();
        $this->sitemaps = Collection::make();
        $this->index    = 1;
    }

    /**
     * @return \Helldar\Sitemap\Services\MakeItem
     */
    public function makeItem()
    {
        return new MakeItem;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder ...$models_builders
     *
     * @deprecated Use a `builders` method instead.
     *
     * @return $this
     */
    public function models(Builder ...$models_builders)
    {
        $this->builders = (array) $models_builders;

        return $this;
    }

    /**
     * Builders of your models.
     *
     * @param \Illuminate\Database\Eloquent\Builder ...$builders
     *
     * @return $this
     */
    public function builders(Builder ...$builders)
    {
        $this->builders = (array) $builders;

        return $this;
    }

    /**
     * @param array $items
     *
     * @return $this
     */
    public function manual(...$items)
    {
        $this->manuals = (array) $items;

        return $this;
    }

    /**
     * Return response with XML data.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show()
    {
        return Response::create((string) $this->get(), 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    /**
     * Save data to files.
     *
     * @param null|string $path
     * @param bool $only_one
     *
     * @return bool|int
     */
    public function save($path = null, $only_one = false)
    {
        $path = $path ?: config('sitemap.filename', public_path('sitemap.xml'));

        if (!$only_one && config('sitemap.separate_files', false)) {
            return (int) $this->saveMany($path);
        }

        return (int) $this->saveOne($path);
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    private function saveOne($path)
    {
        return (bool) file_put_contents($path, $this->get());
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    private function saveMany($path)
    {
        $xml = Xml::init('sitemapindex');

        $directory = Str::finish(pathinfo($path, PATHINFO_DIRNAME), '/');
        $filename  = Str::slug(pathinfo($path, PATHINFO_FILENAME));
        $extension = Str::lower(pathinfo($path, PATHINFO_EXTENSION));

        $this->processManyItems('builders', $this->builders, $directory, $filename, $extension, __LINE__);
        $this->processManyItems('manual', $this->manuals, $directory, $filename, $extension, __LINE__);

        foreach ($this->sitemaps as $sitemap) {
            $xml->addItem($sitemap, 'sitemap');
        }

        return (bool) file_put_contents($path, $xml->get());
    }

    /**
     * @param string $method
     * @param array $items
     * @param string $directory
     * @param string $filename
     * @param string $extension
     */
    private function processManyItems($method, $items, $directory, $filename, $extension, $line = null)
    {
        foreach ($items as $item) {
            $file     = sprintf('%s-%s.%s', $filename, $this->index, $extension);
            $realpath = $directory . $file;

            $loc = Str::after($directory, public_path());
            $loc = url(Str::finish($loc, '/') . $file);

            if (!method_exists($this, $method)) {
                $line    = $line ?: __LINE__;
                $message = sprintf("The '%s' method not exist in %s of %s:%s", $method, get_class(), __FILE__, $line);

                throw new MethodNotExists($message);
            }

            if ($method == 'manual') {
                $item = (new Manual($item))->get();
            }

            (new self)
                ->{$method}($item)
                ->save($realpath, true);

            $item = (new MakeItem)
                ->loc($loc)
                ->lastmod()
                ->get();

            $this->sitemaps->push($item);

            $this->index++;
        }
    }

    /**
     * @return string
     */
    private function get()
    {
        foreach ($this->builders as $builder) {
            $this->processBuilder($builder);
        }

        foreach ($this->manuals as $items) {
            foreach ($items as $item) {
                $this->processManuals($item);
            }
        }

        return $this->xml->get();
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
    private function processManuals($item = [])
    {
        $item = new Collection($item);

        $loc        = $this->e($item->get('loc', config('app.url')));
        $changefreq = $item->get('changefreq', config('sitemap.frequency', 'daily'));
        $lastmod    = Carbon::parse($item->get('lastmod', Carbon::now()))->toAtomString();
        $priority   = (float) $item->get('priority', config('sitemap.priority', 0.5));

        $this->xml->addItem(compact('loc', 'lastmod', 'changefreq', 'priority'));
    }

    /**
     * @param $model_name
     * @param $key
     * @param null $default
     * @param bool $ignore_empty
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
     * @param $item
     * @param bool $field
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
     * @param bool $date_field
     * @param int $age
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    private function getItems(Builder $builder, $date_field = false, $age = 180)
    {
        if ($age && $date_field) {
            $date = Carbon::now()
                ->addDays(-1 * abs((int) $age));

            return $builder
                ->where($date_field, '>', $date)
                ->get();
        }

        return $builder->get();
    }
}
