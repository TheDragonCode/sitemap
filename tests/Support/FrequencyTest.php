<?php

namespace Tests\Support;

use Helldar\Sitemap\Constants\Frequency as Freq;
use Helldar\Sitemap\Support\Frequency;
use PHPUnit\Framework\TestCase;

class FrequencyTest extends TestCase
{
    public function testAll()
    {
        $result = Frequency::all();

        $this->assertIsArray($result);

        $this->assertTrue(in_array(Freq::DAILY, $result));
        $this->assertTrue(in_array(Freq::HOURLY, $result));
        $this->assertTrue(in_array(Freq::MONTHLY, $result));
        $this->assertTrue(in_array(Freq::WEEKLY, $result));
        $this->assertTrue(in_array(Freq::YEARLY, $result));
        $this->assertTrue(in_array(Freq::ALWAYS, $result));
        $this->assertTrue(in_array(Freq::NEVER, $result));

        $this->assertFalse(in_array('foo', $result));
    }

    public function testGet()
    {
        $this->assertEquals(Freq::DAILY, Frequency::get());
        $this->assertEquals(Freq::DAILY, Frequency::get('foo'));
        $this->assertEquals(Freq::DAILY, Frequency::get('Daily'));

        $this->assertEquals(Freq::HOURLY, Frequency::get('Hourly'));
        $this->assertEquals(Freq::HOURLY, Frequency::get('hourly'));

        $this->assertEquals(Freq::MONTHLY, Frequency::get('Monthly'));
        $this->assertEquals(Freq::MONTHLY, Frequency::get('monthly'));

        $this->assertEquals(Freq::WEEKLY, Frequency::get('Weekly'));
        $this->assertEquals(Freq::WEEKLY, Frequency::get('weekly'));

        $this->assertEquals(Freq::YEARLY, Frequency::get('Yearly'));
        $this->assertEquals(Freq::YEARLY, Frequency::get('yearly'));

        $this->assertEquals(Freq::ALWAYS, Frequency::get('Always'));
        $this->assertEquals(Freq::ALWAYS, Frequency::get('always'));

        $this->assertEquals(Freq::NEVER, Frequency::get('Never'));
        $this->assertEquals(Freq::NEVER, Frequency::get('never'));
    }

    public function testExists()
    {
        $this->assertIsBool(Frequency::exists());
        $this->assertIsBool(Frequency::exists(Freq::DAILY));

        $this->assertTrue(Frequency::exists(Freq::DAILY));
        $this->assertTrue(Frequency::exists(Freq::HOURLY));
        $this->assertTrue(Frequency::exists(Freq::MONTHLY));
        $this->assertTrue(Frequency::exists(Freq::WEEKLY));
        $this->assertTrue(Frequency::exists(Freq::YEARLY));
        $this->assertTrue(Frequency::exists(Freq::ALWAYS));
        $this->assertTrue(Frequency::exists(Freq::NEVER));

        $this->assertFalse(Frequency::exists('foo'));
    }
}
