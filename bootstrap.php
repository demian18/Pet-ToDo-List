<?php

use Core\App;
use Core\Container;

$container = new Container();
App::setContainer($container);

$dependencies = require base_path('config/dependencies.php');

foreach ($dependencies as $key => $resolver) {
    App::bind($key, $resolver);
}

/*$container->bind('logger', function() {
    $config = require base_path('config/log.php');
    $log = new Logger('app');

    foreach ($config['channels'] as $channel) {
        $log->pushHandler(new StreamHandler($channel['path'], $channel['level']));
    }

    return $log;
});*/