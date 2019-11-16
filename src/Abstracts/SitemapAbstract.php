<?php

namespace Helldar\Sitemap\Abstracts;

use Helldar\Sitemap\Contracts\ShowableContract;
use Helldar\Sitemap\Support\Xml;
use Helldar\Sitemap\Traits\Support\RunProcess;
use Symfony\Component\HttpFoundation\Response;

abstract class SitemapAbstract implements ShowableContract
{
    use RunProcess;

    /** @var Xml */
    protected $xml;

    public function __construct()
    {
        $this->xml = Xml::init();
    }

    public function show(): Response
    {
        $this->run();

        return Response::create($this->get(), 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    protected function get(): string
    {
        return $this->xml->get();
    }
}
