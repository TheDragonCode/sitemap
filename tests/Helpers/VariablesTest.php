<?php

namespace Helldar\Sitemap\Helpers;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use function count;

class VariablesTest extends TestCase
{
    public function testCorrectPriority()
    {
        $this->assertEquals(0.1, Variables::correctPriority(0.1));
        $this->assertEquals(0.3, Variables::correctPriority(0.3));
        $this->assertEquals(0.5, Variables::correctPriority(0.5));
        $this->assertEquals(0.7, Variables::correctPriority(0.7));
        $this->assertEquals(0.9, Variables::correctPriority(0.9));

        $this->assertEquals(0.5, Variables::correctPriority());
        $this->assertEquals(0.5, Variables::correctPriority(0));
        $this->assertEquals(0.5, Variables::correctPriority(1.1));

        $this->assertEquals(0.5, Variables::correctPriority(-1));
        $this->assertEquals(0.5, Variables::correctPriority(-10));
        $this->assertEquals(0.5, Variables::correctPriority(10));
        $this->assertEquals(0.5, Variables::correctPriority(20));
    }

    public function testGetFrequencies()
    {
        $this->assertIsArray(Variables::getFrequencies());

        $this->assertEquals(7, count(Variables::getFrequencies()));
    }

    public function testCorrectFrequency()
    {
        $this->assertEquals('always', Variables::correctFrequency('always'));
        $this->assertEquals('daily', Variables::correctFrequency('daily'));
        $this->assertEquals('hourly', Variables::correctFrequency('hourly'));
        $this->assertEquals('monthly', Variables::correctFrequency('monthly'));
        $this->assertEquals('never', Variables::correctFrequency('never'));
        $this->assertEquals('weekly', Variables::correctFrequency('weekly'));
        $this->assertEquals('yearly', Variables::correctFrequency('yearly'));

        $this->assertEquals('daily', Variables::correctFrequency());
        $this->assertEquals('daily', Variables::correctFrequency('foo'));
        $this->assertEquals('daily', Variables::correctFrequency('bar'));
        $this->assertEquals('daily', Variables::correctFrequency('baz'));
    }

    public function testGetDate()
    {
        $this->assertTrue(Variables::getDate() instanceof Carbon);

        $this->assertEquals('2019-03-21 21:23:13', Variables::getDate(1553203393)->format('Y-m-d H:i:s'));
        $this->assertEquals('2019-03-21 21:23:13', Variables::getDate('2019-03-21 21:23:13')->format('Y-m-d H:i:s'));
        $this->assertEquals(date('Y-m-d H:i:s'), Variables::getDate()->format('Y-m-d H:i:s'));
    }
}
