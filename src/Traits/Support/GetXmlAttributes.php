<?php

namespace Helldar\Sitemap\Traits\Support;

use DOMElement;
use Helldar\Core\Xml\Facades\Xml;

trait GetXmlAttributes
{
    protected function getXml(): DOMElement
    {
        $xml  = Xml::init();
        $item = $xml->makeItem('url');

        foreach ($this->toArray() as $key => $value) {
            $element = $xml->makeItem($key, $value);

            $xml->appendChild($item, $element);
        }

        return $item;
    }
}
