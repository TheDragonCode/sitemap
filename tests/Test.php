<?php

namespace Tests\Helpers;

use Helldar\Sitemap\Contracts\Sitemap\ItemContract;
use Helldar\Sitemap\Support\Make\Sitemap;
use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    public function testSitemapInstance()
    {
        $value = sitemap();

        $this->assertTrue($value instanceof Sitemap);
    }

    public function testItemInstance()
    {
        $value = sitemap()->item();

        $this->assertTrue($value instanceof ItemContract);
    }
}
