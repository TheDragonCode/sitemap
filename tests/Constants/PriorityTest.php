<?php

namespace Tests\Constants;

use Helldar\Sitemap\Constants\Priority;
use PHPUnit\Framework\TestCase;

class PriorityTest extends TestCase
{
    public function testDefault()
    {
        $this->assertEquals(0.5, Priority::DEFAULT);
    }
}
