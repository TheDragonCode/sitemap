<?php

namespace Helldar\Sitemap\Contracts;

interface StorageContract
{
    static public function put(string $path, $content, string $disk = null): bool;

    static public function url(string $path, string $disk = null): string;

    static public function clear(string $filename);
}
