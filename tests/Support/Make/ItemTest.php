<?php

namespace Tests\Support\Make;

use Carbon\Carbon;
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

    public function testToArray()
    {
        $item = new Item();
        $item->lastmod('2019-11-16 01:13');
        $item->priority(5);
        $item->loc('http://example.com');
        $item->changefreq('foo');

        $result = $item->toArray();

        $expected = [
            'changefreq' => 'daily',
            'lastmod'    => Carbon::parse('2019-11-16 01:13'),
            'loc'        => 'http://example.com',
            'priority'   => 0.5,
        ];

        $this->assertIsArray($result);

        $this->assertArrayHasKey('changefreq', $result);
        $this->assertArrayHasKey('lastmod', $result);
        $this->assertArrayHasKey('loc', $result);
        $this->assertArrayHasKey('priority', $result);

        $this->assertEquals($expected, $result);
    }

    public function testToArrayDefault()
    {
        $item = new Item();
        $item->loc('http://example.com');

        $result = $item->toArray();

        $this->assertIsArray($result);

        $this->assertArrayHasKey('changefreq', $result);
        $this->assertArrayHasKey('lastmod', $result);
        $this->assertArrayHasKey('loc', $result);
        $this->assertArrayHasKey('priority', $result);
    }

    public function testToArrayWithError()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('Argument 1 passed to Helldar\Support\Facades\Http::isUrl() must be of the type string, null given');

        $item = new Item();

        $item->toArray();
    }
}
