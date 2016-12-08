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


class XmlController
{
    /**
     * @var string
     */
    protected static $template = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset %s>%s\n</urlset>";

    /**
     * @var array
     */
    protected static $template_xmlns = [
        'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"',
        'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
        'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"',
    ];

    /**
     * @var string
     */
    protected static $template_url = "\n<url>%s\n</url>";

    /**
     * @var string
     */
    protected static $template_item = "\n\t<{0}>{1}</{0}>";

    /**
     * @author  Andrey Helldar <helldar@ai-rus.com>
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

        return sprintf(static::$template, $xmlns, $items);
    }

    /**
     * @author  Andrey Helldar <helldar@ai-rus.com>
     * @version 2016-12-08
     *
     * @param $items
     *
     * @return string
     */
    private static function compile($items)
    {
        $result = [];

        foreach ($items as $item) {
            $line = '';

            foreach ($item->all() as $key => $value) {
                $line .= static::replaceItem($key, $value);
            }

            $result[] = sprintf(static::$template_url, $line);
        }

        return implode('', $result);
    }

    /**
     * Compile item line.
     *
     * @author  Andrey Helldar <helldar@ai-rus.com>
     * @version 2016-12-08
     *
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    private static function replaceItem($key, $value)
    {
        return str_replace(['{0}', '{1}'], [$key, $value], static::$template_item);
    }
}