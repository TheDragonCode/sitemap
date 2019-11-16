<?php

namespace Helldar\Sitemap\Support;

use Helldar\Sitemap\Contracts\Support\InitableContract;
use Helldar\Sitemap\Contracts\Support\StorageContract;

class Storage implements StorageContract, InitableContract
{
    public static function init()
    {
        return new self();
    }

    public function put(string $filename, string $content)
    {
        // TODO: Implement put() method.
    }

    public function allFiles(string $path)
    {
        // TODO: Implement allFiles() method.
    }

    public function delete(string $filename)
    {
        // TODO: Implement delete() method.
    }
}
