<?php

namespace Helldar\Sitemap\Traits\Support;

use Helldar\Sitemap\Support\Config;
use Helldar\Sitemap\Support\Storage;

trait Storable
{
    /**
     * @param string|null $filename
     *
     * @throws \Helldar\Sitemap\Exceptions\Config\UnknownConfigException
     *
     * @return bool
     */
    public function store(string $filename = null): bool
    {
        $path = $this->path($filename);

        $this->clean($path);

        return $this->isSeparate()
            ? $this->storeMany($path)
            : $this->storeOne($path, $this->get());
    }

    /**
     * @throws \Helldar\Sitemap\Exceptions\Config\UnknownConfigException
     *
     * @return bool
     */
    protected function isSeparate(): bool
    {
        return (bool) Config::get('separate_files', false);
    }

    /**
     * @param string|null $filename
     *
     * @throws \Helldar\Sitemap\Exceptions\Config\UnknownConfigException
     *
     * @return string
     */
    protected function path(string $filename = null): string
    {
        return $filename ?: Config::get('filename', 'sitemap.xml');
    }

    private function clean(string $filename)
    {
        //
    }

    private function storeOne(string $filename, string $content): bool
    {
        return Storage::init()->put($filename, $content);
    }

    private function storeMany(string $filename): bool
    {
        //
    }
}
