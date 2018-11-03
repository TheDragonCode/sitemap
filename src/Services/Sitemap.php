<?php

namespace Helldar\Sitemap\Services;

use Carbon\Carbon;
use Helldar\Sitemap\Exceptions\MethodNotExists;
use Helldar\Sitemap\Helpers\Variables;
use Helldar\Sitemap\Services\Make\Item;
use Helldar\Sitemap\Traits\Helpers;
use Helldar\Sitemap\Traits\Processes\BuilderProcess;
use Helldar\Sitemap\Traits\Processes\ImagesProcess;
use Helldar\Sitemap\Traits\Processes\ManualProcess;
use Helldar\Sitemap\Validators\ImagesValidator;
use Helldar\Sitemap\Validators\ManualValidator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class Sitemap
{
    use Helpers, BuilderProcess, ManualProcess, ImagesProcess;

    /** @var \Helldar\Sitemap\Services\Xml */
    protected $xml;

    /** @var string */
    protected $storage_disk;

    /** @var \Illuminate\Contracts\Filesystem\Filesystem|\Illuminate\Support\Facades\Storage */
    protected $storage;

    /** @var \Illuminate\Support\Collection */
    protected $sitemaps;

    /** @var int */
    protected $index = 1;

    /** @var null|string */
    protected $url = null;

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
        $except = [];

        if ($this->builders || $this->manuals) {
            $except = ['images'];
        }

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

        $this->clearDirectory($path);

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
    protected function saveOne($path, array $except = []): bool
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
    protected function saveMany($path): bool
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
    protected function processManyItems(string $method, array $items, string $directory, string $filename, string $extension, int $line = null)
    {
        $line = $line ?: __LINE__;
        $this->existsMethod($method, $line);

        foreach ($items as $item) {
            if (empty($item)) {
                continue;
            }

            $file = sprintf('%s-%s.%s', $filename, $this->index, $extension);
            $path = $directory . $file;
            $loc  = $this->urlToSitemapFile($path);

            switch ($method) {
                case 'manual':
                    $item = (new ManualValidator($item))->get();
                    break;

                case 'images':
                    $item = (new ImagesValidator($item))->get();
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
    protected function get(array $except = []): string
    {
        array_map(function ($builder) {
            $this->processBuilder($builder);
        }, $this->builders);

        array_map(function ($items) {
            array_map(function ($item) {
                $this->processManuals($item->get());
            }, $items);
        }, $this->manuals);

        if (!in_array('images', $except)) {
            array_map(function ($item) {
                $this->processImages($item);
            }, $this->images);
        }

        return $this->xml->get();
    }

    /**
     * Set the date when the content was last updated.
     *
     * @param mixed $item
     * @param bool $field
     *
     * @return string
     */
    protected function lastmod($item, $field = false): string
    {
        if ($field && $value = $item->{$field}) {
            return Variables::getDate($value)
                ->toAtomString();
        }

        return Carbon::now()->toAtomString();
    }

    /**
     * Getting the URL for the file in case it is split into several files.
     *
     * @param string $path
     *
     * @return string
     */
    protected function urlToSitemapFile($path): string
    {
        $prefix = str_finish($this->url, '/');

        return url($prefix . $path);
    }

    protected function existsMethod(string $method, int $line = null)
    {
        if (!method_exists($this, $method)) {
            $line    = $line ?: __LINE__;
            $message = sprintf("The '%s' method not exist in %s of %s:%s", $method, get_class(), __FILE__, $line);

            throw new MethodNotExists($message);
        }
    }
}
