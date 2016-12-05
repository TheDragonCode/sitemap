<?php

/*
 * This file is part of the Sitemap package.
 *
 * (c) Andrey Helldar <helldar@ai-rus.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://github.com/orchestral/parser
 */

namespace Helldar\Sitemap;


use Carbon\Carbon;

class Sitemap
{
    protected static $cache      = 0;
    protected static $age        = 180;
    protected static $age_column = 'updated_at';

    /**
     * Create a new Sitemap instance.
     *
     * Please see the testing aids section (specifically static::setTestNow())
     * for more on the possibility of this constructor returning a test instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public static function generate()
    {
        return 'checked!';
    }

    private static function makeItems($model, $route, $keys = [], $priority = 0.8)
    {
        $days  = abs(self::$age) * -1;
        $items = $model::where(self::$age_column, '>', Carbon::now()->addDays($days));
    }

    /**
     * Compile skeleton.
     *
     * @param $data
     *
     * @return string
     */
    private static function make($data)
    {
        return '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . $data . '</urlset>';
    }
}