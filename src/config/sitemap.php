<?php

return [
    /*
     * The filename to save.
     */

    'filename' => 'sitemap.xml',

    'formatOutput' => true,

    /*
     * Age data in days, over which references will not be included in the sitemap.
     *
     * Default: 180 days.
     */

    'age' => 180,

    /*
     * For some column search.
     *
     * Default: updated_at.
     */

    'lastmod' => 'updated_at',

    /*
     * This value indicates how frequently the content at a particular URL is likely to change.
     *
     * Default, `daily`.
     */

    'frequency' => 'daily',

    /*
     * Priority for links.
     *
     * Default, 0.8.
     */

    'priority' => 0.8,

    'route_parameters' => ['*'],

    /*
     * Models for searching data.
     */

    'models' => [
        \App\User::class => [
            'route' => 'route.name',
            'route_parameters' => [
                'slug' => 'table_field_for_slug',
                'foo' => 'table_field_for_foo',
                'bar' => 'table_field_for_bar',
            ],
            'lastmod' => 'updated_at',
            'frequency' => 'daily',
            'priority' => 0.8,
        ],
    ],
];
