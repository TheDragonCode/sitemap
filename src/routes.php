<?php
/*
 * This file is part of the Sitemap package.
 *
 * (c) Andrey Helldar <helldar@ai-rus.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

\Illuminate\Support\Facades\Route::group([
    'namespace' => 'Helldar\Sitemap\Controllers',
], function () {
    \Illuminate\Support\Facades\Route::get('sitemap.xml', [
        'as'         => 'sitemap',
        'middleware' => ['web'],
        'uses'       => 'SitemapController@generate',
    ]);
});