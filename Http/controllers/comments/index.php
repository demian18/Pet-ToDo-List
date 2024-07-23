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

$notifications = $db->query('SELECT id, assignee_id, type FROM notifications WHERE task_id = :task_id', [
    'task_id' => $taskId
])->get();

$notification_ids = array_map(function($notification) {
    return $notification['id'];
}, $notifications);

$comments = $db->query('SELECT * FROM comments WHERE not_id IN (' . implode(',', array_fill(0, count($notification_ids), '?')) . ')', $notification_ids)->get();

$assignee_ids = array_map(function($notification) {
    return $notification['assignee_id'];
}, $notifications);

if (in_array($user_id, $assignee_ids)) {
    foreach ($notifications as $notification) {
        if ($notification['assignee_id'] == $user_id) {
            $db->query('UPDATE notifications SET status = "viewed" WHERE id = :id', [
                'id' => $notification['id']
            ]);
        }
    }
}

view('comments/index.view.php', [
    'taskId' => $taskId,
    'notId' => $notification_ids[0],
    'comments' => $comments,
]);
