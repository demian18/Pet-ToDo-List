<?php

use Core\App;
use Core\Database;
use Core\Repository\ProfileRepository;
use Core\Session;

$db = App::resolve(Database::class);

$session_user = Session::get('user');
$email = $session_user['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $taskId = $input['id'];

    if ($taskId) {
        $creator = $db->query('SELECT creator_id FROM tasks WHERE id = :id', [
            'id' => $taskId
        ])->findOrFail();
        $creator_id = $creator['creator_id'];

        $profileRepository = new ProfileRepository();
        $user = $profileRepository->findUser($email);
        $user_id = $user['id'];

        $notifications = $db->query('SELECT id FROM notifications WHERE task_id = :task_id', [
            'task_id' => $taskId
        ])->get();

        if (empty($notifications)) {
            $db->query('INSERT INTO notifications (task_id, creator_id, assignee_id)
                    VALUES (:task_id, :creator_id, :assignee_id)', [
                'task_id' => $taskId,
                'creator_id' => $user_id,
                'assignee_id' => $creator_id,
            ]);

            $db->query('UPDATE tasks set status_id = 4 where id = :id', [
                'id' => $taskId,
            ]);

            echo json_encode(['status' => 'success', 'message' => 'help', 'id' => $taskId, 'creator_notifications' => $user_id]);
        } else {
            echo json_encode(['status' => 'success', 'message' => 'notification already exists']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid task ID']);
    }
}
