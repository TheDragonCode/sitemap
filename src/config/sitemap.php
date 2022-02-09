<?php

return [
    /*
     * Storage name configuration to save files.
     *
     * Default, 'public'.
     */

    'storage' => env('SITEMAP_STORAGE', 'public'),

    /*
     * List of domain names for the formation of links to the site maps in case of their separation.
     * See "separate_files" option.
     */

    'domains' => [
        //'foo' => env('APP_URL') . '/storage',
        //'bar' => 'http://example.com/storage',
    ],

    /*
     * The path to the file.
     *
     * Default, 'sitemap.xml'.
     */

    'filename' => env('SITEMAP_FILENAME', 'sitemap.xml'),

    /*
     * Saving site maps to separated files with a shared file that contains links to others.
     *
     * Default, false.
     */

    'separate_files' => env('SITEMAP_SEPARATE', false),

    // Nicely formats output with indentation and extra space.

    'format_output' => env('SITEMAP_FORMAT_OUTPUT', false),

    /*
     * The number of days that the entry will go to the site map.
     * To disable it, enter 0.
     *
     * Default: 180 days.
     */

    'age' => 180,

    /*
     * The name of the column that contains the timestamp.
     *
     * Default: updated_at.
     */

    'lastmod' => 'updated_at',

    /*
     * This value indicates how frequently the content at a particular URL is likely to change.
     *
     * Available values: always, hourly, daily, weekly, monthly, yearly, never.
     *
     * You can also use constants:
     *
     *   use Helldar\Sitemap\Helpers\Variables;
     *
     *   Variables::FREQUENCY_ALWAYS
     *   Variables::FREQUENCY_DAILY
     *   Variables::FREQUENCY_HOURLY
     *   Variables::FREQUENCY_MONTHLY
     *   Variables::FREQUENCY_NEVER
     *   Variables::FREQUENCY_WEEKLY
     *   Variables::FREQUENCY_YEARLY
     *
     * Default, `daily`.
     */

    'frequency' => 'daily',

    /*
     * Priority for links.
     *
     * Default, 0.5.
     */

    'priority' => 0.5,

    /*
     * List of parameters for generating URL, where:
     *  - key is the name of the parameter in the routing.
     *  - value is the name of the column in the collection.
     */

    'route_parameters' => [
        // 'slug' => 'table_field_for_slug',
        // 'foo'  => 'table_field_for_foo',
        // 'bar'  => 'table_field_for_bar',
        // 'baz',
    ],

    // Models for searching data.

    'models' => [
        //\App\User::class => [
        //    'route' => 'route.name',
        //    'route_parameters' => [
        //        'slug' => 'table_field_for_slug',
        //        'foo'  => 'table_field_for_foo',
        //        'bar'  => 'my_relation.slug',
        //        'baz',
        //    ],
        //    'age'       => 180,
        //    'lastmod'   => 'updated_at',
        //    'frequency' => 'daily',
        //    'priority'  => 0.5,
        //],
    ],
];
