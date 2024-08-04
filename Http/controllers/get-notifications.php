<?php

use Core\App;
use Core\Database;
use Core\Repository\UserRepository;
use Core\Services\User;
use Core\Session;

$session_user = Session::get('user');
$email = $session_user['email'];

$db = App::resolve(Database::class);

$userService = new User(App::resolve(UserRepository::class));
$user = $userService->findByEmail($email);
$user_id = $user->getId();

$notification_count = $db->query('SELECT COUNT(*) as count FROM notifications WHERE assignee_id = :assignee_id AND status = "new"', [
    'assignee_id' => $user_id
])->findOrFail();

header('Content-Type: application/json');
echo json_encode([
    'count' => $notification_count['count'],
]);