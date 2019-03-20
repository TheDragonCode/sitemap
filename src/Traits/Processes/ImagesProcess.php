<?php

namespace Helldar\Sitemap\Traits\Processes;

use DOMElement;
use Helldar\Sitemap\Helpers\Variables;
use Helldar\Sitemap\Services\Make\Images;
use Helldar\Sitemap\Services\Make\Item;
use Helldar\Sitemap\Services\Xml;
use Helldar\Sitemap\Validators\ImagesValidator;
use Illuminate\Support\Collection;

trait ImagesProcess
{
    /** @var \Helldar\Sitemap\Services\Xml */
    protected $xml;

    /** @var array */
    protected $images = [];

    protected $chunk_count = 1000;

    public function makeImages(): Images
    {
        return new Images;
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

    protected function processManyImages(string $method, array $items, string $directory, string $filename, string $extension, int $line = null)
    {
        $line = $line ?: __LINE__;
        $this->existsMethod($method, $line);

        $chunk = array_chunk($items, $this->chunk_count);

        foreach ($chunk as $images) {
            $file = \sprintf('%s-%s.%s', $filename, $this->index, $extension);
            $path = $directory . $file;
            $loc  = $this->urlToSitemapFile($path);

            $array = Variables::toArray($images);

            new ImagesValidator($array);

            (new self)
                ->{$method}($images)
                ->saveOne($path, []);

            $make_item = (new Item)
                ->loc($loc)
                ->lastmod()
                ->get();

            $this->sitemaps->push($make_item);

            $this->index++;
        }
    }

    private function processImageSection(Collection $item)
    {
        $images = $item->get('images', []);

        if (!$images) {
            return;
        }

        $xml = $this->xml->makeItem('url');

        if ($loc = $item->get('loc')) {
            $loc = $this->xml->makeItem('loc', $this->e($loc));
            $this->xml->appendChild($xml, $loc);
        }

        \array_map(function ($image) use (&$xml) {
            $this->processImageImages($xml, $image);
        }, $item->get('images', []));

        $this->xml->appendToRoot($xml);
    }

    private function processImageImages(DOMElement &$xml, array $image = [])
    {
        $element = $this->xml->makeItem('image:image');

        \array_map(function ($key, $value) use (&$element) {
            if ($key == 'loc') {
                $value = $this->e($value);
            }

            $el = $this->xml->makeItem('image:' . $key, $value);
            $this->xml->appendChild($element, $el);
        }, \array_keys($image), \array_values($image));

        $this->xml->appendChild($xml, $element);
    }

    private function makeXml()
    {
        $this->xml = Xml::init('urlset', [
            'xmlns'       => 'http://www.sitemaps.org/schemas/sitemap/0.9',
            'xmlns:image' => 'http://www.google.com/schemas/sitemap-image/1.1',
        ]);
    }

    private function pushImage(Images $image)
    {
        \array_push($this->images, $image);
    }
}
