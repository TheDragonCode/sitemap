<?php
/*
 * This file is part of the Sitemap package.
 *
 * (c) Andrey Helldar <helldar@ai-rus.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helldar\Sitemap;

use Carbon\Carbon;
use Helldar\Sitemap\Controllers\SitemapController;

class Factory extends SitemapController
{
    /**
     * @var array
     */
    protected $items_overflow = [];

    /**
     * Adding points to sitemap.
     *
     * @author  Andrey Helldar <helldar@ai-rus.com>
     *
     * @version 2016-12-08
     *
     * @param string $loc      The correct URL address.
     * @param int    $lastmod  The timestamp of last modification.
     * @param float  $priority Priority for current URL. Default, 0.8.
     */
    public function set($loc, $lastmod, $priority)
    {
        if (empty($loc) || !static::check_migration()) {
            return;
        }

        $item = [];

        $item['loc']      = trim($loc);
        $item['priority'] = !empty($priority) ? (float)$priority : static::$default_priority;

        if (!empty($lastmod)) {
            $item['lastmod'] = Carbon::createFromTimestamp($lastmod)->format('Y-m-d');
        }

        if (parent::$frequency) {
            $result['changefreq'] = parent::$frequency;
        }

        $this->items_overflow[] = collect($item);
    }

    /**
     * Check table in database.
     *
     * @author  Andrey Helldar <helldar@ai-rus.com>
     * @version 2016-12-21
     *
     * @return bool
     */
    private function check_migration()
    {
        if (!\Schema::hasTable('sitemaps')) {
            die("Database table `sitemaps` not found.");
        }

        return true;
    }

    /**
     * Getting the compiled data.
     *
     * @author  Andrey Helldar <helldar@ai-rus.com>
     *
     * @version 2016-12-08
     *
     * @return mixed
     */
    public function get()
    {
        return parent::compile($this->items_overflow);
    }
}
