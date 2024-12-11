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
    'qs' => [
        'version' => '6.13.1',
    ],
    'side-channel' => [
        'version' => '1.0.6',
    ],
    'get-intrinsic' => [
        'version' => '1.2.4',
    ],
    'call-bind/callBound' => [
        'version' => '1.0.7',
    ],
    'object-inspect' => [
        'version' => '1.13.1',
    ],
    'es-errors/type' => [
        'version' => '1.3.0',
    ],
    'es-errors' => [
        'version' => '1.3.0',
    ],
    'es-errors/eval' => [
        'version' => '1.3.0',
    ],
    'es-errors/range' => [
        'version' => '1.3.0',
    ],
    'es-errors/ref' => [
        'version' => '1.3.0',
    ],
    'es-errors/syntax' => [
        'version' => '1.3.0',
    ],
    'es-errors/uri' => [
        'version' => '1.3.0',
    ],
    'has-symbols' => [
        'version' => '1.0.3',
    ],
    'has-proto' => [
        'version' => '1.0.1',
    ],
    'function-bind' => [
        'version' => '1.1.2',
    ],
    'hasown' => [
        'version' => '2.0.0',
    ],
    'set-function-length' => [
        'version' => '1.2.1',
    ],
    'es-define-property' => [
        'version' => '1.0.0',
    ],
    'define-data-property' => [
        'version' => '1.1.2',
    ],
    'has-property-descriptors' => [
        'version' => '1.0.1',
    ],
    'gopd' => [
        'version' => '1.0.1',
    ],
];
