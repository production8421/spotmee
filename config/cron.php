<?php

return [

    /*
    |--------------------------------------------------------------------------
    | HTTP cron secret
    |--------------------------------------------------------------------------
    |
    | Shared secret for GET /cron/run?token=... (hosting panel URL cron).
    | Generate a long random string and set CRON_SECRET in .env on the server.
    |
    */

    'secret' => env('CRON_SECRET'),

];
