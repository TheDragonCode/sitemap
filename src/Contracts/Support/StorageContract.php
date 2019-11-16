<?php

namespace Helldar\Sitemap\Contracts\Support;

interface StorageContract
{
    public function put(string $filename, string $content): bool;

    public function allFiles(string $path): bool;

    public function delete(string $filename): bool;
}
