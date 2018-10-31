<?php

namespace Helldar\Sitemap\Services;

use Carbon\Carbon;
use Helldar\Sitemap\Exceptions\MethodNotExists;
use Helldar\Sitemap\Helpers\Variables;
use Helldar\Sitemap\Services\Items\MakeItem;
use Helldar\Sitemap\Traits\Helpers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class Sitemap
{
    use Helpers;

    /**
     * @var string
     */
    private $storage_disk;

    /**
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    private $storage;

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
    private $index = 1;

    /**
     * @var null|string
     */
    private $url = null;

    /**
     * Sitemap constructor.
     */
    public function __construct()
    {
        $this->xml      = Xml::init();
        $this->sitemaps = collect();

        $this->storage_disk = config('sitemap.storage', 'public');
        $this->storage      = Storage::disk($this->storage_disk);
    }

    /**
     * Call the element creation mechanism.
     *
     * @return \Helldar\Sitemap\Services\Items\MakeItem
     */
    public function makeItem(): MakeItem
    {
        return new MakeItem;
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

    /**
     * Set domain name for using in multidomain application.
     *
     * @param string $domain
     *
     * @return \Helldar\Sitemap\Services\Sitemap
     */
    public function domain($domain): self
    {
        $config = config('sitemap.domains', []);
        $config = collect($config);
        $url    = $config->get($domain);

        if (is_null($url)) {
            $config  = config("filesystems.disks.{$this->storage_disk}");
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
        return Response::create((string) $this->get(), 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    /**
     * Saving data to files.
     *
     * @param null|string $path
     *
     * @return int
     */
    public function save($path = null): int
    {
        $path  = $path ?: config('sitemap.filename', 'sitemap.xml');
        $count = sizeof($this->builders) + sizeof($this->manuals);

        if ($count > 1 && config('sitemap.separate_files', false)) {
            return (int) $this->saveMany($path);
        }

        return (int) $this->saveOne($path);
    }

    /**
     * Save data to file.
     *
     * @param string $path
     *
     * @return bool
     */
    private function saveOne($path): bool
    {
        return $this->storage->put($path, $this->get());
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
    private function processManyItems($method, $items, $directory, $filename, $extension, $line = null)
    {
        foreach ($items as $item) {
            $file = sprintf('%s-%s.%s', $filename, $this->index, $extension);
            $path = $directory . $file;
            $loc  = $this->urlToSitemapFile($path);

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
                ->save($path);

            $make_item = (new MakeItem)
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
     * @return string
     */
    private function get(): string
    {
        array_map(function ($builder) {
            $this->processBuilder($builder);
        }, $this->builders);

        array_map(function ($items) {
            array_map(function ($item) {
                $this->processManuals($item);
            }, $items);
        }, $this->manuals);

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
    private function processManuals($item = [])
    {
        $item = collect($item);

        $loc        = $this->e($item->get('loc', config('app.url')));
        $changefreq = Variables::correctFrequency($item->get('changefreq', config('sitemap.frequency', 'daily')));
        $lastmod    = Variables::getDate($item->get('lastmod'))->toAtomString();
        $priority   = Variables::correctPriority($item->get('priority', config('sitemap.priority', 0.5)));

        $this->xml->addItem(compact('loc', 'lastmod', 'changefreq', 'priority'));
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
        $value = config("sitemap.models.{$model_name}.{$key}", null);

        if ($value || !$ignore_empty) {
            return $value;
        }

        return config("sitemap.{$key}", $default);
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
}
