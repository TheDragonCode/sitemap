<?php
/*
 * This file is part of the Sitemap package.
 *
 * (c) Andrey Helldar <helldar@ai-rus.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helldar\Sitemap\Controllers;

use Carbon\Carbon;

class SitemapController
{
    /**
     * @var string
     */
    protected static $filename = 'sitemap.xml';

    /**
     * Caching time in minutes.
     * Set `0` to disable cache.
     * Default: 0.
     *
     * @var int
     */
    protected static $cache = 0;

    /**
     * Age data in minutes, over which references will not be included in the sitemap.
     * Default: 180 days.
     *
     * @var int
     */
    protected static $age = 180;

    /**
     * For some column search.
     * Default: updated_at.
     *
     * @var string
     */
    protected static $age_column = 'updated_at';

    /**
     * This value indicates how frequently the content at a particular URL is likely to change.
     *
     * @var string
     */
    protected static $frequency = 'daily';

    /**
     * The time the URL was last modified.
     * This information allows crawlers to avoid redrawing documents that haven't changed.
     *
     * @var bool
     */
    protected static $last_modification = true;

    /**
     * Models for searching data.
     *
     * @var array
     */
    protected static $items = array();

    /**
     * Create a new Sitemap instance.
     *
     * Please see the testing aids section (specifically static::setTestNow())
     * for more on the possibility of this constructor returning a test instance.
     */
    public function __construct()
    {
        static::$cache = config('sitemap.cache', 0);
        static::$age = config('sitemap.age', 180);
        static::$age_column = config('sitemap.age_column', 'updated_at');
        static::$frequency = config('sitemap.frequency', 'daily');
        static::$last_modification = config('sitemap.last_modification', true);
        static::$items = config('sitemap.items', array());
    }

    /**
     * Generate sitemap.
     *
     * @author  Andrey Helldar <helldar@ai-rus.com>
     *
     * @version 2016-12-05
     *
     * @return mixed
     */
    public static function generate()
    {
        if (static::$cache) {
            $path = public_path(static::$filename);

            if (file_exists($path)) {
                $updated = Carbon::createFromTimestamp(filemtime($path));
                $diff = Carbon::now()->diffInMinutes($updated);

                if (abs($diff) > abs(static::$cache)) {
                    unlink($path);
                    $items = static::compile();
                    file_put_contents($path, $items);
                } else {
                    $items = file_get_contents($path);
                }
            } else {
                $items = static::compile();
                file_put_contents($path, $items);
            }
        }

        if (empty($items)) {
            $items = static::compile();
        }

        return $items;
    }

    private static function compile()
    {
        $items = static::get();

        return view('sitemap::skeleton')->with(compact('items'));
    }

    /**
     * Get data;
     *
     * @author  Andrey Helldar <helldar@ai-rus.com>
     *
     * @version 2016-12-05
     *
     * @return array
     */
    private static function get()
    {
        $result = array();

        foreach (static::$items as $item) {
            $result = array_merge($result, static::items($item));
        }

        return $result;
    }

    /**
     * Get items from database.
     *
     * @author  Andrey Helldar <helldar@ai-rus.com>
     *
     * @version 2016-12-05
     *
     * @param $item
     *
     * @return array
     */
    private static function items($item)
    {
        $minutes = abs(static::$age) * -1;
        $records = ($item['model'])::where(static::$age_column, '>', Carbon::now()->addMinutes($minutes))->get();
        $result = array();

        if ($records->count()) {
            foreach ($records as $record) {
                $result[] = static::make($item, $record);
            }
        }

        return $result;
    }

    /**
     * Make item.
     *
     * @author  Andrey Helldar <helldar@ai-rus.com>
     *
     * @version 2016-12-05
     *
     * @param $item
     * @param $record
     *
     * @return \Illuminate\Support\Collection
     */
    private static function make($item, $record)
    {
        $route_keys = array();

        foreach ($item['keys'] as $key => $value) {
            $route_keys[$key] = $record->{$value};
        }

        $result['loc'] = route($item['route'], $route_keys);

        if (static::$last_modification) {
            $result['lastmod'] = Carbon::parse($record->{static::$age_column})->format('Y-m-d');
        }

        if (static::$frequency) {
            $result['changefreq'] = static::$frequency;
        }

        if (!empty($item['priority'])) {
            $result['priority'] = $item['priority'];
        }

        return collect($result);
    }
}
