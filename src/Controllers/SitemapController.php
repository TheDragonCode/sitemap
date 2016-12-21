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
    protected static $table_name = 'sitemaps';

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
     * Age data in days, over which references will not be included in the sitemap.
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
     * Default priority for all links.
     *
     * @var float
     */
    protected static $default_priority = 0.8;

    /**
     * Model for searching data.
     *
     * @var array
     */
    protected static $items = [];

    /**
     * @var string
     */
    protected static $compiled_data = '';

    /**
     * Create a new Sitemap instance.
     *
     * Please see the testing aids section (specifically static::setTestNow())
     * for more on the possibility of this constructor returning a test instance.
     *
     * @param null|string $filename
     */
    public function __construct($filename = null)
    {
        static::create($filename);
    }

    /**
     * @author  Andrey Helldar <helldar@ai-rus.com>
     *
     * @version 2016-12-08
     */
    private static function create($filename)
    {
        static::$filename          = isset($filename) ? $filename : config('sitemap.filename', 'sitemap.xml');
        static::$cache             = config('sitemap.cache', 0);
        static::$age               = config('sitemap.age', 180);
        static::$age_column        = config('sitemap.age_column', 'updated_at');
        static::$frequency         = config('sitemap.frequency', 'daily');
        static::$last_modification = config('sitemap.last_modification', true);
        static::$items             = config('sitemap.items', []);
    }

    /**
     * Generate sitemap.
     *
     * @author  Andrey Helldar <helldar@ai-rus.com>
     *
     * @version 2016-12-05
     *
     * @param string|null $filename
     *
     * @return mixed
     */
    public static function generate($filename = null)
    {
        if (!empty($filename)) {
            static::$filename = $filename;
        }

        if (static::$cache) {
            $path = public_path(static::$filename);

            if (file_exists($path)) {
                $updated = Carbon::createFromTimestamp(filemtime($path));
                $diff    = Carbon::now()->diffInMinutes($updated);

                if (abs($diff) > abs(static::$cache)) {
                    static::$compiled_data = static::compile();
                    static::save();
                } else {
                    static::$compiled_data = file_get_contents($path);
                }
            } else {
                static::$compiled_data = static::compile();
                static::save();
            }
        }

        if (empty(static::$compiled_data)) {
            static::$compiled_data = static::compile();
        }

        return static::$compiled_data;
    }

    /**
     * The compilation of the data into XML.
     *
     * @author  Andrey Helldar <helldar@ai-rus.com>
     *
     * @version 2016-12-05
     *
     * @param array $items_overflow
     *
     * @return mixed
     */
    protected static function compile($items_overflow = null)
    {
        static::header();
        $items = !empty($items_overflow) ? $items_overflow : static::merge();

        static::$compiled_data = XmlController::create($items);

        static::save();

        return static::$compiled_data;
    }

    /**
     * Set document type in Header.
     *
     * @author  Andrey Helldar <helldar@ai-rus.com>
     *
     * @version 2016-12-08
     */
    private static function header()
    {
        header('Content-Type: application/xml');
    }

    /**
     * Combining the data in one array.
     *
     * @author  Andrey Helldar <helldar@ai-rus.com>
     *
     * @version 2016-12-05
     *
     * @return array
     */
    private static function merge()
    {
        $result = [];

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
        $days    = abs(static::$age) * -1;
        $records = ($item['model'])::where(static::$age_column, '>', Carbon::now()->addDays($days))->get();
        $result  = [];

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
        $route_keys = [];

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

    /**
     * Save compiled data into file.
     *
     * @author  Andrey Helldar <helldar@ai-rus.com>
     *
     * @version 2016-12-08
     */
    public static function save()
    {
        $path = public_path(static::$filename);

        if (file_exists($path)) {
            unlink($path);
        }

        file_put_contents($path, static::$compiled_data);
    }

    /**
     * Insert or update record in database.
     *
     * @author  Andrey Helldar <helldar@ai-rus.com>
     * @version 2016-12-21
     *
     * @param $item
     */
    public static function insertDb($item)
    {
        if (\DB::table(static::$table_name)->whereLoc($item['loc'])->count()) {
            \DB::table(static::$table_name)->whereLoc($item['loc'])->update($item);
        } else {
            \DB::table(static::$table_name)->insert(array_merge($item, [
                'created_at' => Carbon::now(),
            ]));
        }
    }

    /**
     * Delete old records from the database.
     *
     * @author  Andrey Helldar <helldar@ai-rus.com>
     * @version 2016-12-21
     *
     */
    public static function clearDb()
    {
        if (config('sitemap.clear_old', false)) {
            $time = Carbon::now()->addDays(config('sitemap.age', 180));
            \DB::table(static::$table_name)->where(config('sitemap.age_column', 'updated_at'), '<=', $time)->delete();
        }
    }
}
