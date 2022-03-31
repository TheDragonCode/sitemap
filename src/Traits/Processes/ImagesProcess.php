<?php

namespace Helldar\Sitemap\Traits\Processes;

use DOMElement;
use DragonCode\Core\Xml\Facades\Xml;
use DragonCode\Core\Xml\Helpers\Arr;
use DragonCode\Core\Xml\Helpers\Str;
use Helldar\Sitemap\Services\Make\Images;
use Helldar\Sitemap\Services\Make\Item;
use Helldar\Sitemap\Validators\ImagesValidator;
use Illuminate\Support\Collection;
use function array_chunk;
use function array_keys;
use function array_map;
use function array_push;
use function array_values;
use function config;
use function sprintf;

trait ImagesProcess
{
    /** @var \DragonCode\Core\Xml\Facades\Xml */
    protected $xml;

    /** @var array */
    protected $images = [];

    protected $chunk_count = 1000;

    public function makeImages(): Images
    {
        return new Images();
    }

    /**
     * @param \Helldar\Sitemap\Services\Make\Images ...$images
     *
     * @return \Helldar\Sitemap\Traits\Processes\ImagesProcess
     */
    public function images(...$images): self
    {
        foreach ($images as $image) {
            if ($image instanceof Images) {
                $this->pushImage($image);
            } else {
                foreach ($image as $item) {
                    $this->pushImage($item);
                }
            }
        }

        return $this;
    }

    protected function processImages(Images $image)
    {
        $item = new Collection($image->get());

        $this->processImageSection($item);
    }

    protected function processManyImages(string $method, array $items, string $directory, string $filename, string $extension, ?int $line = null)
    {
        $line = $line ?: __LINE__;
        $this->existsMethod($method, $line);

        $chunk = array_chunk($items, $this->chunk_count);

        foreach ($chunk as $images) {
            $file = sprintf('%s-%s.%s', $filename, $this->index, $extension);
            $path = $directory . $file;
            $loc  = $this->urlToSitemapFile($path);

            $array = Arr::toArray($images);

            new ImagesValidator($array);

            (new self())
                ->{$method}($images)
                ->saveOne($path, []);

            $make_item = (new Item())
                ->loc($loc)
                ->lastmod()
                ->get();

            $this->sitemaps->push($make_item);

            ++$this->index;
        }
    }

    private function processImageSection(Collection $item)
    {
        $images = $item->get('images', []);

        if (! $images) {
            return;
        }

        $xml = $this->xml->makeItem('url');

        if ($loc = $item->get('loc')) {
            $loc = $this->xml->makeItem('loc', Str::e($loc));
            $this->xml->appendChild($xml, $loc);
        }

        array_map(function ($image) use (&$xml) {
            $this->processImageImages($xml, $image);
        }, $item->get('images', []));

        $this->xml->appendToRoot($xml);
    }

    private function processImageImages(DOMElement &$xml, array $image = [])
    {
        $element = $this->xml->makeItem('image:image');

        array_map(function ($key, $value) use (&$element) {
            if ($key == 'loc') {
                $value = Str::e($value);
            }

            $el = $this->xml->makeItem('image:' . $key, $value);
            $this->xml->appendChild($element, $el);
        }, array_keys($image), array_values($image));

        $this->xml->appendChild($xml, $element);
    }

    private function makeXml()
    {
        $root = 'urlset';

        $attributes = [
            'xmlns'       => 'http://www.sitemaps.org/schemas/sitemap/0.9',
            'xmlns:image' => 'http://www.google.com/schemas/sitemap-image/1.1',
        ];

        $format_output = config('sitemap.format_output', true);

        $this->xml = Xml::init($root, $attributes, $format_output);
    }

    private function pushImage(Images $image)
    {
        array_push($this->images, $image);
    }
}
