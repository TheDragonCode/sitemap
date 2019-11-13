<?php

namespace Helldar\Sitemap\Contracts\Sitemap;

use Symfony\Component\HttpFoundation\Response;

interface ShowableContract
{
    public function show(): Response;
}
