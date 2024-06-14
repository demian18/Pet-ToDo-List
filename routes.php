<?php

$router->get('/', 'controllers/index.php');
$router->post('/create-task', 'controllers/create-task.php');
$router->delete('/delete-task', 'controllers/delete-task.php');

$router->get('/edit-task', 'controllers/edit-task.php')->only('auth');
$router->patch('/update-task', 'controllers/update-task.php');

$router->get('/register', 'controllers/register/create.php')->only('guest');
$router->post('/register', 'controllers/register/store.php');