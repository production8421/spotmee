<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Published static assets (relative to /public)
    |--------------------------------------------------------------------------
    |
    | Copy the template's `assets` folder to public/{path}. Override via .env
    | if you host assets on a CDN.
    |
    */

    'assets_path' => env('CUBA_ASSETS_PATH', 'vendor/cuba/assets'),

];
