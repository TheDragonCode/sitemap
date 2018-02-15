<?php

namespace Helldar\Sitemap\Services;

class Xml
{
    /**
     * @var \DOMDocument
     */
    private $doc;

    /**
     * @var \DOMElement
     */
    private $root;

    /**
     * Xml constructor.
     */
    public function __construct()
    {
        $this->doc = (new \DOMDocument('1.0', 'utf-8'));

        $this->root = $this->doc->createElement('urlset');

        $this->root->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $this->root->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $this->root->setAttribute('xsi:schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');

        $this->doc->formatOutput = (bool)config('sitemap.formatOutput', true);
    }

    /**
     * Initialization Xml service from static sources.
     *
     * @return \Helldar\Sitemap\Services\Xml
     */
    public static function init()
    {
        return (new self());
    }

    /**
     * @param array $parameters
     */
    public function addItem($parameters = [])
    {
        ksort($parameters);

        $section = $this->doc->createElement('url');

        foreach ($parameters as $key => $value) {
            $elem = $this->doc->createElement($key, $value);
            $section->appendChild($elem);
        }

        $this->root->appendChild($section);
    }

    /**
     * @return string
     */
    public function get()
    {
        $this->doc->appendChild($this->root);

        return $this->doc->saveXML();
    }
}
