<?php

namespace Tests\Support;

use Helldar\Sitemap\Support\Priority;
use PHPUnit\Framework\TestCase;
use TypeError;

class PriorityTest extends TestCase
{
    public function testGet()
    {
        $this->assertEquals(0.1, Priority::get(0.1));
        $this->assertEquals(0.2, Priority::get(0.2));
        $this->assertEquals(0.3, Priority::get(0.3));
        $this->assertEquals(0.4, Priority::get(0.4));
        $this->assertEquals(0.5, Priority::get(0.5));
        $this->assertEquals(0.6, Priority::get(0.6));
        $this->assertEquals(0.7, Priority::get(0.7));
        $this->assertEquals(0.8, Priority::get(0.8));
        $this->assertEquals(0.9, Priority::get(0.9));
        $this->assertEquals(1.0, Priority::get(1.0));

        $this->assertEquals(0.5, Priority::get());
        $this->assertEquals(0.5, Priority::get(0));
        $this->assertEquals(0.5, Priority::get(10));
        $this->assertEquals(0.5, Priority::get(100));
    }

    public function testFailed()
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionCode(0);

        $this->assertEquals(true, Priority::get('foo'));
    }
}
