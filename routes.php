<?php

$router->get('/', 'controllers/index.php',);
$router->post('/create-task', 'controllers/create-task.php',);
$router->delete('/delete-task', 'controllers/delete-task.php',);

$router->get('/register', 'controllers/register/create.php',);
$router->post('/register', 'controllers/register/store.php',);