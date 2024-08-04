<?php

$router->get('/', [\Http\controllers\TaskController::class, 'index']);
$router->post('/create-task', [\Http\controllers\TaskController::class, 'create'])->only('auth');
$router->delete('/delete-task', [\Http\controllers\TaskController::class, 'delete'])->only('auth');

$router->get('/edit-task', [\Http\controllers\TaskController::class, 'edit'])->only('auth');
$router->patch('/update-task', [\Http\controllers\TaskController::class, 'update'])->only('auth');

$router->get('/register', [\Http\controllers\UserController::class, 'create_register'])->only('guest');
$router->post('/register', [\Http\controllers\UserController::class, 'register']);

$router->get('/login', [\Http\controllers\UserController::class, 'create_session'])->only('guest');
$router->post('/session', [\Http\controllers\UserController::class, 'login'])->only('guest');
$router->delete('/session', [\Http\controllers\UserController::class, 'logout'])->only('auth');

$router->get('/profile', [\Http\controllers\ProfileController::class, 'index'])->only('auth');
$router->get('/edit-profile', [\Http\controllers\ProfileController::class, 'edit'])->only('auth');
$router->patch('/update-profile', [\Http\controllers\ProfileController::class, 'update'])->only('auth');

$router->post('/update-task-status', '/task-handl.php')->only('auth');

$router->post('/filter-tasks', '/filter-tasks.php')->only('auth');

$router->post('/help-task', '/help-tasks.php')->only('auth');
$router->post('/cancel-task', '/cancel-task.php')->only('auth');

$router->post('/get-notifications', '/get-notifications.php')->only('auth');

$router->get('/notifications', '/notifications/index.php')->only('auth');
$router->post('/close-notice', '/notifications/close-notice.php')->only('auth');
$router->get('/task-comments', '/comments/index.php')->only('auth');
$router->post('/create-comment', '/comments/create.php')->only('auth');

$router->get('/tasks', '/admin/index.php')->only('auth');
$router->post('/cancel-task-admin', '/admin/cancel-task.php')->only('auth');