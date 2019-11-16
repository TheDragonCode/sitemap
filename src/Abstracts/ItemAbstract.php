<?php

namespace Helldar\Sitemap\Abstracts;

use DOMElement;
use Helldar\Sitemap\Contracts\Sitemap\ItemContract;
use Helldar\Sitemap\Traits\Elements\Frequenciable;
use Helldar\Sitemap\Traits\Elements\LastModifiable;
use Helldar\Sitemap\Traits\Elements\Locationable;
use Helldar\Sitemap\Traits\Elements\Prioritiable;
use Helldar\Sitemap\Traits\Support\GetAttributes;
use Helldar\Sitemap\Traits\Support\GetXmlAttributes;

abstract class ItemAbstract implements ItemContract
{
    use Frequenciable;
    use LastModifiable;
    use Locationable;
    use Prioritiable;
    use GetAttributes;
    use GetXmlAttributes;

    public function toArray(): array
    {
        return $this->getAttributes();
    }

    public function toXml(): DOMElement
    {
        return $this->getXml();
    }
}
