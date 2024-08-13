<?php

use Core\App;
use Core\Database;
use Core\Repository\NotificationsRepository;
use Core\Repository\UserRepository;
use Core\Services\Notifications;
use Core\Services\User;
use Core\Session;

$session_user = Session::get('user');
$email = $session_user['email'];

$db = App::resolve(Database::class);

$userService = new User(App::resolve(UserRepository::class));
$user = $userService->findByEmail($email);
$user_id = $user->id;

$notService = new Notifications(App::resolve(NotificationsRepository::class));
$notification_count = $notService->get_count_not($user_id);

header('Content-Type: application/json');
echo json_encode([
    'count' => $notification_count,
]);