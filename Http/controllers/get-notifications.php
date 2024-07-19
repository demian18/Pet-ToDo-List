<?php

use Core\App;
use Core\Database;
use Core\Repository\ProfileRepository;
use Core\Session;

$session_user = Session::get('user');
$email = $session_user['email'];

$db = App::resolve(Database::class);

$profileRepository = new ProfileRepository();
$user = $profileRepository->findUser($email);
$user_id = $user['id'];

$notifications = $db->query('SELECT * FROM notifications WHERE creator_id = :creator_id AND status = 0', [
    'creator_id' => $user_id
])->get();

header('Content-Type: application/json');
echo json_encode($notifications);