<?php

use Core\App;
use Core\Database;
use Core\Repository\ProfileRepository;
use Core\Session;

$session_user = Session::get('user');
$email = $session_user['email'];

$comment = $_POST['comment'];
$task_id = $_POST['task_id'];
$not_id = $_POST['not_id'];

$db = App::resolve(Database::class);

$profileRepository = new ProfileRepository();
$user = $profileRepository->findUser($email);

$user_id = $user['id'];
$user_role = $user['role_id'];

$notification = $db->query('SELECT id, task_id, assignee_id, creator_id 
                            FROM notifications
                            WHERE id = :not_id', [
    'not_id' => $not_id,
])->findOrFail();

if ($user_role == 2) {
    $assignee_id = $notification['creator_id'];
} else {
    $assignee_id = $notification['assignee_id'];
}

$db->query('INSERT INTO comments (comment, not_id, task_id, creator_id, assignee_id) VALUES (:comment, :not_id, :task_id, :creator_id, :assignee_id)', [
    'comment' => $comment,
    'not_id' => $not_id,
    'task_id' => $task_id,
    'creator_id' => $user_id,
    'assignee_id' => $assignee_id
]);

$db->query('INSERT INTO notifications (task_id, creator_id, assignee_id, type, status) VALUES (:task_id, :creator_id, :assignee_id, :type, :status)', [
    'task_id' => $task_id,
    'creator_id' => $user_id,
    'assignee_id' => $assignee_id,
    'type' => 'comment',
    'status' => 'new'
]);

header('Location: /notifications');
exit();
