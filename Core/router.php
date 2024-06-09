<?php
$uri = parse_url($_SERVER['REQUEST_URI'])['path'];

$routes = [
    '/' => 'controllers/index.php',
    '/create-task' => 'controllers/create-task.php',
    '/delete-task' => 'controllers/delete-task.php',
];

function routeToControoler($uri, $routes)
{
    if (array_key_exists($uri, $routes)){
        require base_path($routes[$uri]);
    } else{
        abort();
    }
}

function abort($code = 404)
{
    http_response_code($code);

    require ("views/{$code}.php");

    die();
}

routeToControoler($uri, $routes);