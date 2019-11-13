<?php

namespace Helldar\Sitemap\Contracts;

interface StorageContract
{
    public static function put(string $path, $content, string $disk = null): bool;

    public static function url(string $path, string $disk = null): string;

    public static function clear(string $filename);
}
