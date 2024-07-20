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

$notifications = $db->query('SELECT n.id, n.task_id, n.assignee_id, u.email, n.type, n.status, n.created_at 
                            FROM notifications n
                            JOIN users u ON n.creator_id = u.id
                            WHERE n.assignee_id = :assignee_id 
                            AND n.status != "completed"', [
    'assignee_id' => $user_id
])->get();

$unique_notifications = [];

foreach ($notifications as $notification) {
    $task_id = $notification['task_id'];
    $type = $notification['type'];

    if (isset($unique_notifications[$task_id]) && $type == 'comment') {
        continue;
    }

    $unique_notifications[$task_id] = $notification;
}

$notifications_to_display = array_values($unique_notifications);

view('notifications/index.view.php', [
    'notifications' => $notifications_to_display
]);