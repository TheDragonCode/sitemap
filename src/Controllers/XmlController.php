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

class XmlController
{
    /**
     * @var string
     */
    protected static $template = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n%s\n<urlset %s>%s\n</urlset>";

    /**
     * @var array
     */
    protected static $template_xmlns = array(
        'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"',
        'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
        'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"',
    );

    /**
     * @var string
     */
    protected static $template_url = "\n<url>%s\n</url>";

    /**
     * @var string
     */
    protected static $template_item = "\n\t<{0}>{1}</{0}>";

    /**
     * @var string
     */
    private static $comment = "<!--\n\tCreated with andrey-helldar/sitemap\n\tai-rus.com\n-->";

    /**
     * @author  Andrey Helldar <helldar@ai-rus.com>
     *
     * @version 2016-12-08
     *
     * @param $items
     *
     * @return string
     */
    public static function create($items)
    {
        $items = static::compile($items);
        $xmlns = implode(' ', static::$template_xmlns);

        return sprintf(static::$template, static::$comment, $xmlns, $items);
    }

    /**
     * @author  Andrey Helldar <helldar@ai-rus.com>
     *
     * @version 2016-12-08
     *
     * @param $items
     *
     * @return string
     */
    private static function compile($items)
    {
        $result = array();

        foreach ($items as $item) {
            $item = static::checkType($item);
            $line = '';

            foreach ($item as $key => $value) {
                $line .= static::replaceItem($key, $value);
            }

            $result[] = sprintf(static::$template_url, $line);
        }

        return implode('', $result);
    }

    /**
     * Check type of item.
     *
     * @author  Andrey Helldar <helldar@ai-rus.com>
     *
     * @version 2016-12-21
     *
     * @param $item
     *
     * @return \Illuminate\Support\Collection
     */
    private static function checkType($item)
    {
        if (gettype($item) == 'object') {
            unset($item->created_at);
            unset($item->updated_at);

            if (isset($item->lastmod)) {
                $item->lastmod = static::fixLastmod($item->lastmod);
            }

            return collect($item);
        }

        if (isset($item->lastmod)) {
            $item->lastmod = static::fixLastmod($item->lastmod);
        }

        return $item;
    }

    /**
     * Fix lastmod value.
     *
     * @author  Andrey Helldar <helldar@ai-rus.com>
     *
     * @version 2016-12-21
     *
     * @param $lastmod
     *
     * @return string
     */
    private static function fixLastmod($lastmod)
    {
        if (stripos((string) $lastmod, ' ') !== false) {
            return Carbon::parse($lastmod)->format('Y-m-d');
        }

        return $lastmod;
    }

    /**
     * Compile item line.
     *
     * @author  Andrey Helldar <helldar@ai-rus.com>
     *
     * @version 2016-12-08
     *
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    private static function replaceItem($key, $value)
    {
        return str_replace(array('{0}', '{1}'), array($key, $value), static::$template_item);
    }
}
