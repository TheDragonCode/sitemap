<?php

namespace Helldar\Sitemap\Services;

use DOMDocument;
use DOMElement;
use Illuminate\Support\Facades\Config;

class Xml
{
    /**
     * @var DOMDocument
     */
    private $doc;

    /**
     * @var DOMElement
     */
    private $root;

    /**
     * Xml constructor.
     *
     * @param string $root
     * @param array $attributes
     */
    public function __construct($root = 'urlset', array $attributes = [])
    {
        $this->doc  = new DOMDocument('1.0', 'utf-8');
        $this->root = $this->doc->createElement($root);

        $attributes = $attributes ?: ['xmlns' => 'http://www.sitemaps.org/schemas/sitemap/0.9'];
        $this->setAttributes($this->root, $attributes);

        $this->doc->formatOutput = (bool) Config::get('sitemap.formatOutput', true);
    }

    /**
     * Initialization Xml service from static sources.
     *
     * @param string $root
     * @param array $attributes
     *
     * @return \Helldar\Sitemap\Services\Xml
     */
    public static function init($root = 'urlset', array $attributes = []): self
    {
        return new self($root, $attributes);
    }

    /**
     * @param array $parameters
     * @param string $element_name
     */
    public function addItem(array $parameters = [], string $element_name = 'url')
    {
        ksort($parameters);

        $section = $this->doc->createElement($element_name);

        foreach ($parameters as $key => $value) {
            $elem = $this->doc->createElement($key, $value);
            $section->appendChild($elem);
        }

        $this->root->appendChild($section);
    }

    /**
     * @return string
     */
    public function get(): string
    {
        $this->doc->appendChild($this->root);

        return $this->doc->saveXML();
    }

    /**
     * Adds new attribute
     *
     * @see  https://php.net/manual/en/domelement.setattribute.php
     *
     * @param \DOMElement $element
     * @param array $attributes
     */
    private function setAttributes(DOMElement &$element, array $attributes = [])
    {
        foreach ($attributes as $name => $value) {
            $element->setAttribute($name, $value);
        }
    }
}
