<?php

namespace Tests\Support\Make;

use Helldar\Sitemap\Contracts\Sitemap\ItemContract;
use Helldar\Sitemap\Support\Make\Sitemap;
use PHPUnit\Framework\TestCase;

class SitemapTest extends TestCase
{
    public function testItem()
    {
        $obj = new Sitemap();

        $this->assertTrue($obj->item() instanceof ItemContract);
    }

    public function testShow()
    {
        $obj = new Sitemap();

        $expected = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"/>\n";

        $this->assertEquals($expected, $obj->show());
    }
}
