<?php

namespace Helldar\Sitemap\Services;

use Helldar\Sitemap\Contracts\Sitemap\StorableContract;

class Storing implements StorableContract
{
    static public function save(string $path = null, array ...$items): bool
    {
        $is_separate = self::allowSeparate();
        $path        = self::path($path);

        Storage::clear($path);
    }

    static private function allowSeparate(): bool
    {
        return Config::get('separate_files', false);
    }

    static private function path(string $path = null): string
    {
        return $path ?: Config::get('filename', 'sitemap.xml');
    }

    static private function saveOne(string $path, array $except = ['images']): bool
    {
        return Storage::put($path);
    }

    static private function saveMany(string $path): bool
    {

    }
}
