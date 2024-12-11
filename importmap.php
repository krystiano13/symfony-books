<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    'home' => [
        'path' => './assets/home.js',
        'entrypoint' => true,
    ],
    'create' => [
        'path' => './assets/create.js',
        'entrypoint' => true,
    ],
    'update' => [
        'path' => './assets/update.js',
        'entrypoint' => true,
    ],
    'register' => [
        'path' => './assets/register.js',
        'entrypoint' => true,
    ],
    'login' => [
        'path' => './assets/login.js',
        'entrypoint' => true,
    ],
    'menu' => [
        'path' => './assets/menu.js',
        'entrypoint' => true,
    ],
    'jwt-decode' => [
        'version' => '4.0.0',
    ],
];
