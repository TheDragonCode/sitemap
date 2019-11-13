<?php

namespace Helldar\Sitemap\Services;

use Helldar\Core\Xml\Facades\Xml as CoreXml;

class Xml
{
    protected $xml;

    protected $root = 'urlset';

    protected $attributes = ['xmlns' => 'http://www.sitemaps.org/schemas/sitemap/0.9'];

    protected $format_output = false;

    public function __construct()
    {
        $this->setFormatOutput();
        $this->init();

        return $this->get();
    }

    private function get(): CoreXml
    {
        return $this->xml;
    }

    private function setFormatOutput()
    {
        $this->format_output = Config::get('format_output', false);
    }

    private function init()
    {
        $this->xml = CoreXml::init($this->root, $this->attributes, $this->format_output);
    }
}
