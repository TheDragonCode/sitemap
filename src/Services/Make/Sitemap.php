<?php

namespace Helldar\Sitemap\Services\Make;

use Helldar\Contracts\Sitemap\SitemapContract;
use Helldar\Sitemap\Services\Xml;
use Helldar\Sitemap\Traits\BuildersProcess;
use Helldar\Sitemap\Traits\Domain;
use Helldar\Sitemap\Traits\ManualsProcess;
use Helldar\Sitemap\Traits\Process;
use Symfony\Component\HttpFoundation\Response;

class Sitemap implements SitemapContract
{
    use BuildersProcess;
    use ManualsProcess;
    use Domain;
    use Process;

    public function __construct(Xml $xml)
    {
        $this->xml = $xml;
    }

    public function show(): Response
    {
        $except = [];

        if ($this->builders || $this->manuals) {
            $except = ['images'];
        }

        return Response::create((string) $this->get($except), 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    public function save(): bool
    {
        // TODO: Implement save() method.
    }
}
