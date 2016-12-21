<?php
/*
 * This file is part of the Sitemap package.
 *
 * (c) Andrey Helldar <helldar@ai-rus.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
return array(
    /*
     * The file name to save.
     */
    'filename' => 'sitemap.xml',

    /*
     * Caching time in minutes.
     * Set `0` to disable cache.
     * Default: 0.
     */
    'cache' => 0,

    /*
     * Clear old links in database table.
     * Default, `false`.
     */
    'clear_old' => false,

    /*
     * Age data in days, over which references will not be included in the sitemap.
     * Default: 180 days.
     */
    'age' => 180,

    /*
     * For some column search.
     * Default: updated_at.
     */
    'age_column' => 'updated_at',

    /*
     * This value indicates how frequently the content at a particular URL is likely to change.
     * Default, `daily`.
     */
    'frequency' => 'daily',

    /*
     * The time the URL was last modified.
     * This information allows crawlers to avoid redrawing documents that haven't changed.
     */
    'last_modification' => true,

    /*
     * Models for searching data.
     */
    'items' => array(
        /*
         * `model`    Model for data retrieval.
         * `route`    Name of route for return URL.
         * `keys`     `Key for route` => `Column in database`
         *            Example:
         *              Route::get('user/{category}/{id}', ...)->name('user::show');
         *            return:
         *              http://mysite.com/user/10/2 for `category_id` = 10 and `user_id` = 2
         * `priority` Priority for save sitemap. Default: 0.8
         */
        array(
            'model' => \App\User::class,
            'route' => 'user::show',
            'keys' => array(
                'category' => 'category_id',
                'id' => 'user_id',
            ),
            'priority' => 0.8,
        ),
    ),
);
