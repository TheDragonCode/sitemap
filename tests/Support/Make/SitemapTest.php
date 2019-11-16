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
        $obj    = new Sitemap();
        $result = $obj->show();

        $expected = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"/>\n";

        $this->assertEquals($expected, $result->getContent());
    }

    public function testItemsShow()
    {
        $obj = new Sitemap();

        $items = [];

        for ($i = 0; $i < 3; $i++) {
            $items[] = $obj->item()
                ->loc('http://example.com/' . $i)
                ->lastmod('2019-11-16 20:08');
        }

        $result = $obj->manual($items);
        $result = $result->show();

        $expected =
            "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"><url><changefreq>daily</changefreq><lastmod>2019-11-16 20:08:00</lastmod><loc>http://example.com/0</loc><priority>0.5</priority></url><url><changefreq>daily</changefreq><lastmod>2019-11-16 20:08:00</lastmod><loc>http://example.com/1</loc><priority>0.5</priority></url><url><changefreq>daily</changefreq><lastmod>2019-11-16 20:08:00</lastmod><loc>http://example.com/2</loc><priority>0.5</priority></url></urlset>\n";

        $this->assertEquals($expected, $result->getContent());
    }

    public function testItemsShowTwo()
    {
        $obj = new Sitemap();

        $items = [];

        for ($i = 0; $i < 3; $i++) {
            $items[] = $obj->item()
                ->loc('http://example.com/' . $i)
                ->lastmod('2019-11-16 20:08');
        }

        $result = $obj->manual($items, $items);
        $result = $result->show();

        $expected =
            "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"><url><changefreq>daily</changefreq><lastmod>2019-11-16 20:08:00</lastmod><loc>http://example.com/0</loc><priority>0.5</priority></url><url><changefreq>daily</changefreq><lastmod>2019-11-16 20:08:00</lastmod><loc>http://example.com/1</loc><priority>0.5</priority></url><url><changefreq>daily</changefreq><lastmod>2019-11-16 20:08:00</lastmod><loc>http://example.com/2</loc><priority>0.5</priority></url><url><changefreq>daily</changefreq><lastmod>2019-11-16 20:08:00</lastmod><loc>http://example.com/0</loc><priority>0.5</priority></url><url><changefreq>daily</changefreq><lastmod>2019-11-16 20:08:00</lastmod><loc>http://example.com/1</loc><priority>0.5</priority></url><url><changefreq>daily</changefreq><lastmod>2019-11-16 20:08:00</lastmod><loc>http://example.com/2</loc><priority>0.5</priority></url></urlset>\n";

        $this->assertEquals($expected, $result->getContent());
    }
}
