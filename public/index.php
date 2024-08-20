<?php

use Core\Session;
use Illuminate\Validation\ValidationException;

require_once 'init.php';

$router = new \Core\Router();

$routes = require base_path('routes.php');

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

try {
    $router->route($uri, $method);
} catch (ValidationException $exception) {
    Session::flash('errors', $exception->validator->errors()->toArray());
    Session::flash('old', $exception->validator->getData());

    return redirect($router->previousUrl());
}

Session::unflash();