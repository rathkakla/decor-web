<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Diperlukan oleh client berbasis browser (Flutter Web, panel web, dsb).
    | Client mobile native (Dio/HTTP) tidak terpengaruh aturan ini.
    |
    | Catatan: file statis di public/storage tidak melewati Laravel, jadi
    | header CORS-nya diatur di public/.htaccess.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'storage/*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
