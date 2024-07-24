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

$user_role = $user["role_id"];
$user_id = $user['id'];

$tasks = $db->query('SELECT 
                    tasks.id AS task_id,
                    tasks.title AS task_title,
                    status.status AS task_status,
                    users.email AS user_email
                    FROM tasks
                    JOIN users ON tasks.assignee_id = users.id
                    JOIN status ON tasks.status_id = status.id
                    WHERE creator_id = :creator_id', [
    'creator_id' => $user_id
])->get();

view('admin/index.view.php', [
    "tasks" => $tasks
]);