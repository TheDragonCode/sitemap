<?php

namespace Helldar\Sitemap\Abstracts;

use Helldar\Sitemap\Contracts\Sitemap\ItemContract;
use Helldar\Sitemap\Traits\Elements\Frequenciable;
use Helldar\Sitemap\Traits\Elements\LastModifiable;
use Helldar\Sitemap\Traits\Elements\Locationable;
use Helldar\Sitemap\Traits\Elements\Prioritiable;

abstract class ItemAbstract implements ItemContract
{
    use Frequenciable;
    use LastModifiable;
    use Locationable;
    use Prioritiable;
}
