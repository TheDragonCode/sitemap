<?php

namespace Helldar\Sitemap\Contracts;

use DOMElement;

interface XmlableContract
{
    public function toXml(): DOMElement;
}
