<?php

namespace Helldar\Sitemap\Support;

use Helldar\Core\Xml\Facades\Xml as CoreXml;
use Helldar\Sitemap\Contracts\GetableContract;
use Helldar\Sitemap\Contracts\Support\InitableContract;

class Xml implements InitableContract, GetableContract
{
    /** @var CoreXml */
    protected $xml;

    protected $root = 'urlset';

    protected $attributes = ['xmlns' => 'http://www.sitemaps.org/schemas/sitemap/0.9'];

    protected $format_output = false;

    /**
     * @throws \Helldar\Sitemap\Exceptions\Config\UnknownConfigException
     */
    public function __construct()
    {
        $this->setFormatOutput();
        $this->set();
    }

    public static function init()
    {
        return new self();
    }

    public function get(): string
    {
        return $this->xml->get();
    }

    /**
     * @throws \Helldar\Sitemap\Exceptions\Config\UnknownConfigException
     */
    private function setFormatOutput()
    {
        $this->format_output = Config::get('format_output', false);
    }

    private function set()
    {
        $this->xml = CoreXml::init($this->root, $this->attributes, $this->format_output);
    }
}
