<?php

use Core\App;
use Core\Database;
use Core\Repository\ProfileRepository;
use Core\Session;

$session_user = Session::get('user');
$email = $session_user['email'];

$comment = $_POST['comment'];
$task_id = $_POST['task_id'];

$db = App::resolve(Database::class);

$profileRepository = new ProfileRepository();
$user = $profileRepository->findUser($email);

$user_id = $user['id'];
$user_role = $user['role_id'];

$notifications = $db->query('SELECT id, task_id, assignee_id, creator_id 
                            FROM notifications
                            WHERE type = :type', [
    'type' => 'help',
])->get();

if ($notifications) {
    foreach ($notifications as $notification) {
        if ($user_role == 2) {
            $db->query('INSERT INTO comments (comment, not_id, task_id, creator_id, assignee_id) VALUES (:comment, :not_id, :task_id, :creator_id, :assignee_id)', [
                'comment' => $comment,
                'not_id' => $notification['id'],
                'task_id' => $task_id,
                'creator_id' => $user_id,
                'assignee_id' => $notification['creator_id']
            ]);
            $db->query('INSERT INTO notifications (task_id, creator_id, assignee_id, type)
                    VALUES (:task_id, :creator_id, :assignee_id, :type)', [
                'task_id' => $task_id,
                'creator_id' => $user_id,
                'assignee_id' => $notification['creator_id'],
                'type' => 'comment',
            ]);

            header('location: /notifications');
            exit();
        } else {
            $db->query('INSERT INTO comments (comment, not_id, task_id, creator_id, assignee_id) VALUES (:comment, :not_id, :task_id, :creator_id, :assignee_id)', [
                'comment' => $comment,
                'not_id' => $notification['id'],
                'task_id' => $task_id,
                'creator_id' => $user_id,
                'assignee_id' => $notification['assignee_id']
            ]);
            $db->query('INSERT INTO notifications (task_id, creator_id, assignee_id, type)
                    VALUES (:task_id, :creator_id, :assignee_id, :type)', [
                'task_id' => $task_id,
                'creator_id' => $user_id,
                'assignee_id' => $notification['assignee_id'],
                'type' => 'comment',
            ]);
            header('location: /notifications');
            exit();
        }
    }
}
