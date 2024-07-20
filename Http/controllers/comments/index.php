<?php

use Core\App;
use Core\Database;
use Core\Repository\ProfileRepository;
use Core\Session;

$taskId = $_GET['id'];

$session_user = Session::get('user');
$email = $session_user['email'];

$db = App::resolve(Database::class);

$profileRepository = new ProfileRepository();
$user = $profileRepository->findUser($email);

$user_id = $user['id'];
$user_role = $user['role_id'];

$comments = $db->query('SELECT * FROM comments')->get();

view('comments/index.view.php', [
    'taskId' => $taskId,
    'comments' => $comments,
]);