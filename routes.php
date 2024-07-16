<?php

$router->get('/', '/index.php');
$router->post('/create-task', '/create-task.php')->only('auth');
$router->delete('/delete-task', '/delete-task.php')->only('auth');

$router->get('/edit-task', '/edit-task.php')->only('auth');
$router->patch('/update-task', '/update-task.php')->only('auth');

$router->get('/register', '/register/create.php')->only('guest');
$router->post('/register', '/register/store.php');

$router->get('/login', '/session/create.php')->only('guest');
$router->post('/session', '/session/store.php')->only('guest');
$router->delete('/session', '/session/destroy.php')->only('auth');

$router->get('/profile', '/profile/index.php')->only('auth');
$router->get('/edit-profile', '/profile/edit.php')->only('auth');
$router->patch('/update-profile', '/profile/update.php')->only('auth');

$router->post('/update-task-status', '/task-handl.php')->only('auth');

$router->post('/filter-tasks', '/filter-tasks.php')->only('auth');