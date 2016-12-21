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

        $item['updated_at'] = Carbon::now();
        $item['loc']        = trim($loc);
        $item['priority']   = !empty($priority) ? (float)$priority : static::$default_priority;

        if (!empty($lastmod)) {
            $lastmod         = gettype($lastmod) === 'integer' ? $lastmod : strtotime($lastmod);
            $item['lastmod'] = Carbon::createFromTimestamp($lastmod)->format('Y-m-d');
        }

        parent::insertDb($item);
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
        if (!\Schema::hasTable(parent::$table_name)) {
            die("Database table `" . parent::$table_name . "` not found.");
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
        $where = Carbon::now()->addDays(-1 * abs((int)config('sitemap.age', 180)));
        $items = \DB::table(parent::$table_name)->where(config('sitemap.age_column', 'updated_at'), '>', $where)->get();

        return parent::compile($items);
    }
}
