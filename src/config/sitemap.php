<?php

return [
    /*
     * Nicely formats output with indentation and extra space.
     *
     * Default, false.
     */

    'format_output' => env('SITEMAP_FORMAT_OUTPUT', false),

    /*
     * Saving site maps to separated files with a shared file that contains links to others.
     *
     * Default, false.
     */

    'separate_files' => env('SITEMAP_SEPARATE', false),

    /*
     * The path to the file.
     *
     * Default, 'sitemap.xml'.
     */

    'filename' => env('SITEMAP_FILENAME', 'sitemap.xml'),

];
