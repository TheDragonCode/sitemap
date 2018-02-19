<?php

return [
    /*
     * The path to the file.
     *
     * Default, 'sitemap.xml'.
     */

    'filename' => public_path('sitemap.xml'),

    /*
     * Nicely formats output with indentation and extra space.
     */

    'formatOutput' => true,

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

    /*
     * Models for searching data.
     */

    'models' => [
        //\App\User::class => [
        //    'route'            => 'route.name',
        //    'route_parameters' => [
        //        'slug' => 'table_field_for_slug',
        //        'foo'  => 'table_field_for_foo',
        //        'bar'  => 'table_field_for_bar',
        //        'baz',
        //    ],
        //    'age'              => 180,
        //    'lastmod'          => 'updated_at',
        //    'frequency'        => 'daily',
        //    'priority'         => 0.5,
        //],
    ],
];
