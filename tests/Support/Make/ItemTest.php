<?php

namespace Tests\Support\Make;

use Helldar\Sitemap\Contracts\Sitemap\ItemContract;
use Helldar\Sitemap\Support\Make\Item;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    public function testLastmod()
    {
        $item = new Item();
        $item->lastmod('2019-11-16 01:13');

        $this->assertTrue($item instanceof ItemContract);
    }

    public function testPriority()
    {
        $item = new Item();
        $item->priority(5);

        $this->assertTrue($item instanceof ItemContract);
    }

    /**
     * @throws \Helldar\Support\Exceptions\NotValidUrlException
     */
    public function testLoc()
    {
        $item = new Item();
        $item->loc('http://example.com');

        $this->assertTrue($item instanceof ItemContract);
    }

    public function testChangefreq()
    {
        $item = new Item();
        $item->changefreq('foo');

        $this->assertTrue($item instanceof ItemContract);
    }
}
