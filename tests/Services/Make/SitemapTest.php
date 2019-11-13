<?php

namespace Tests\Services\Make;

use Helldar\Contracts\Sitemap\ItemContract;
use Helldar\Sitemap\Services\Make\Sitemap;
use Helldar\Sitemap\Services\Xml;
use PHPUnit\Framework\TestCase;

class SitemapTest extends TestCase
{
    public function testShow()
    {
    }

    public function testManual()
    {
    }

    public function testSave()
    {
    }

    public function testBuilders()
    {
    }

    public function testItem()
    {
        $xml = new Xml();
        $obj = new Sitemap($xml);

        $this->assertTrue($obj->item() instanceof ItemContract);
    }
}
