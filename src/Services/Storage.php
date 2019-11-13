<?php

namespace Helldar\Sitemap\Services;

use Helldar\Sitemap\Contracts\StorageContract;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage as IlluminateStorage;

class Storage implements StorageContract
{
    static public function put(string $path, $content, string $disk = null): bool
    {
        return self::driver($disk)->put($path, $content);
    }

    static public function url(string $path, string $disk = null): string
    {
        return self::driver($disk)->url($path);
    }

    static public function clear(string $filename)
    {
        // TODO: make removing files from the FTP storage
    }

    static private function disk(string $value = null): string
    {
        return $value ?: Config::get('storage', 'public');
    }

    static private function driver(string $disk = null): Filesystem
    {
        return IlluminateStorage::disk(
            self::disk($disk)
        );
    }
}
