<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

return [
    'default' => 'daily',

    'channels' => [
        'daily' => [
            'driver' => 'daily',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => Logger::DEBUG,
            'days' => 7,
        ],
    ],
];
