<?php

namespace Tests\Support;

use Helldar\Sitemap\Support\Xml;
use PHPUnit\Framework\TestCase;

class XmlTest extends TestCase
{
    public function testGet()
    {
        $obj = new Xml();

        $this->assertTrue(is_string($obj->get()));
    }

    public function testDoc()
    {
        $obj = new Xml();

        $expect = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"/>\n";

        $this->assertEquals($expect, $obj->get());
    }
}
