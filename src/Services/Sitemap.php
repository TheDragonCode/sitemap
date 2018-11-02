<?php

namespace Helldar\Sitemap\Services;

use Carbon\Carbon;
use Helldar\Sitemap\Exceptions\MethodNotExists;
use Helldar\Sitemap\Helpers\Variables;
use Helldar\Sitemap\Interfaces\SitemapInterface;
use Helldar\Sitemap\Services\Make\Images;
use Helldar\Sitemap\Services\Make\Item;
use Helldar\Sitemap\Traits\Helpers;
use Helldar\Sitemap\Validators\Images;
use Helldar\Sitemap\Validators\Manual;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class Sitemap implements SitemapInterface
{
    use Helpers;

    /** @var string */
    private $storage_disk;

    /** @var \Illuminate\Contracts\Filesystem\Filesystem|\Illuminate\Support\Facades\Storage */
    private $storage;

    /** @var array */
    private $builders = [];

    /** @var array */
    private $manuals = [];

    /** @var array */
    private $images = [];

    /** @var \Illuminate\Support\Collection */
    private $sitemaps;

    /** @var \Helldar\Sitemap\Services\Xml */
    private $xml;

    /** @var int */
    private $index = 1;

    /** @var null|string */
    private $url = null;

    /**
     * Sitemap constructor.
     */
    public function __construct()
    {
        $this->xml      = Xml::init();
        $this->sitemaps = collect();

        $this->storage_disk = Config::get('sitemap.storage', 'public');
        $this->storage      = Storage::disk($this->storage_disk);
    }

    public function makeItem(): Item
    {
        return new Item;
    }

    public function makeImages(): Images
    {
        return new Images;
    }

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
     * Send a set of manually created items for processing.
     *
     * @param array $items
     *
     * @return \Helldar\Sitemap\Services\Sitemap
     */
    public function manual(array ...$items): self
    {
        $this->manuals = (array) $items;

        return $this;
    }

    public function images(array ...$images): self
    {
        $this->images = (array) $images;

        return $this;
    }

    /**
     * Set domain name for using in multidomain application.
     *
     * @param string $domain
     *
     * @return \Helldar\Sitemap\Services\Sitemap
     */
    public function domain($domain): self
    {
        $config = Config::get('sitemap.domains', []);
        $config = collect($config);
        $url    = $config->get($domain);

        if (is_null($url)) {
            $config  = Config::get("filesystems.disks.{$this->storage_disk}");
            $collect = collect($config);
            $url     = $collect->get('url', '/');
        }

        $this->url = str_finish($url, '/');

        return $this;
    }

    /**
     * Return response with XML data.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(): Response
    {
        $except = ['images'];

        return Response::create((string) $this->get($except), 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    /**
     * Saving data to files.
     *
     * @param null|string $path
     * @param array $except
     *
     * @return int
     */
    public function save($path = null, array $except = ['images']): int
    {
        $path  = $path ?: Config::get('sitemap.filename', 'sitemap.xml');
        $count = sizeof($this->builders) + sizeof($this->manuals) + sizeof($this->images);

        if ($count > 1 && Config::get('sitemap.separate_files', false)) {
            return (int) $this->saveMany($path);
        }

        return (int) $this->saveOne($path, $except);
    }

    /**
     * Save data to file.
     *
     * @param string $path
     * @param null|array $except
     *
     * @return bool
     */
    private function saveOne($path, array $except = []): bool
    {
        return $this->storage->put($path, $this->get($except));
    }

    /**
     * Save items to multiple files.
     *
     * @param string $path
     *
     * @return bool
     */
    private function saveMany($path): bool
    {
        $xml = Xml::init('sitemapindex');

        $directory = Str::finish(pathinfo($path, PATHINFO_DIRNAME), '/');
        $filename  = Str::slug(pathinfo($path, PATHINFO_FILENAME));
        $extension = Str::lower(pathinfo($path, PATHINFO_EXTENSION));

        $this->processManyItems('builders', $this->builders, $directory, $filename, $extension, __LINE__);
        $this->processManyItems('manual', $this->manuals, $directory, $filename, $extension, __LINE__);
        $this->processManyItems('images', $this->images, $directory, $filename, $extension, __LINE__);

        foreach ($this->sitemaps as $sitemap) {
            $xml->addItem($sitemap, 'sitemap');
        }

        return $this->storage->put($path, $xml->get());
    }

    /**
     * Start the processing of elements.
     *
     * @param string $method
     * @param array|\Illuminate\Support\Collection $items
     * @param string $directory
     * @param string $filename
     * @param string $extension
     * @param null|int $line
     */
    private function processManyItems(string $method, array $items, string $directory, string $filename, string $extension, int $line = null)
    {
        $line = $line ?: __LINE__;
        $this->existsMethod($method, $line);

        foreach ($items as $item) {
            $file = sprintf('%s-%s.%s', $filename, $this->index, $extension);
            $path = $directory . $file;
            $loc  = $this->urlToSitemapFile($path);

            switch ($method) {
                case 'manual':
                    $item = (new Manual($item))->get();
                    break;

                case 'images':
                    $item = (new Images($item))->get();
                    break;
            }

            (new self)
                ->{$method}($item)
                ->save($path, []);

            $make_item = (new Item)
                ->loc($loc)
                ->lastmod()
                ->get();

            $this->sitemaps->push($make_item);

            $this->index++;
        }
    }

    /**
     * Retrieving the result of the processing of the elements in the case of saving all the data without dividing it into several files.
     *
     * @param array $except
     *
     * @return string
     */
    private function get(array $except = []): string
    {
        if (!in_array('builder', $except)) {
            array_map(function ($builder) {
                $this->processBuilder($builder);
            }, $this->builders);
        }

        if (!in_array('manuals', $except)) {
            array_map(function ($items) {
                array_map(function ($item) {
                    $this->processManuals($item);
                }, $items);
            }, $this->manuals);
        }

        if (!in_array('images', $except)) {
            array_map(function ($items) {
                array_map(function ($item) {
                    $this->processImages($item);
                }, $items);
            }, $this->images);
        }

        return $this->xml->get();
    }

    /**
     * Read configuration patterns and generate a link to save to the site map.
     *
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
            $loc     = $this->e(route($route, $params));

            $this->xml->addItem(compact('loc', 'lastmod', 'changefreq', 'priority'));
        }
    }

    /**
     * Reading the configuration of the manually transferred items and creating a link for saving to the sitemap.
     *
     * @param array $item
     */
    private function processManuals(array $item = [])
    {
        $item = collect($item);

        $loc        = $this->e($item->get('loc', Config::get('app.url')));
        $changefreq = Variables::correctFrequency($item->get('changefreq', Config::get('sitemap.frequency', 'daily')));
        $lastmod    = Variables::getDate($item->get('lastmod'))->toAtomString();
        $priority   = Variables::correctPriority($item->get('priority', Config::get('sitemap.priority', 0.5)));

        $this->xml->addItem(compact('loc', 'lastmod', 'changefreq', 'priority'));
    }

    private function processImages(array $item = [])
    {
        // TODO: Add image processing logic.
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
    private function config($model_name, $key, $default = null, $ignore_empty = true)
    {
        $value = Config::get("sitemap.models.{$model_name}.{$key}", null);

        if ($value || !$ignore_empty) {
            return $value;
        }

        return Config::get("sitemap.{$key}", $default);
    }

    /**
     * Set the date when the content was last updated.
     *
     * @param mixed $item
     * @param bool $field
     *
     * @return string
     */
    private function lastmod($item, $field = false): string
    {
        if ($field && $value = $item->{$field}) {
            return Variables::getDate($value)
                ->toAtomString();
        }

        return Carbon::now()->toAtomString();
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
    private function getItems(Builder $builder, $date_field = false, $age = 180)
    {
        if ($age && $date_field) {
            $date = Carbon::now()->addDays(-1 * abs((int) $age));

            return $builder
                ->where($date_field, '>', $date)
                ->get();
        }

        return $builder->get();
    }

    /**
     * Getting the URL for the file in case it is split into several files.
     *
     * @param string $path
     *
     * @return string
     */
    private function urlToSitemapFile($path): string
    {
        $prefix = str_finish($this->url, '/');

        return url($prefix . $path);
    }

    private function existsMethod(string $method, int $line = null)
    {
        if (!method_exists($this, $method)) {
            $line    = $line ?: __LINE__;
            $message = sprintf("The '%s' method not exist in %s of %s:%s", $method, get_class(), __FILE__, $line);

            throw new MethodNotExists($message);
        }
    }
}
