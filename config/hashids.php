<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the connections below you wish to use as
    | your default connection for all work. Of course, you may use many
    | connections at once using the manager class.
    |
    */

    'default' => 'main',

    /*
    |--------------------------------------------------------------------------
    | Hashids Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the connections setup for your application. Example
    | configuration has been included, but you may add as many connections as
    | you would like.
    |
    */

    'connections' => [

        'main' => [
            'salt'     => '40f85e99-0fdf-4b64-9d52-eaffa1fdb161',
            'length'   => 8,
            'alphabet' => '0123456789abcdefghijklmnopqrstuvwxyz'
        ],
        'subscriptions' => [
            'salt'     => '40d85g92-5dde-5b62-1g52-ebffa1fdb161',
            'length'   => 32,
            'alphabet' => '0123456789abcdefghijklmnopqrstuvwxyz'
        ],


    ]

];
