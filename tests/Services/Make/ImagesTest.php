<?php

namespace Helldar\Sitemap\Services\Make;

use PHPUnit\Framework\TestCase;

class ImagesTest extends TestCase
{
    public function testImage()
    {
        $service = new Images();

        $this->assertTrue($service->loc('foo') instanceof Images);
        $this->assertTrue($service->image('foo') instanceof Images);

        $this->assertIsArray($service->get());
    }
}
