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
        $task = $db->query('UPDATE tasks SET status_id = 1 WHERE id = :id', [
            'id' => $taskId
        ]);

        $profileRepository = new ProfileRepository();

        $user = $profileRepository->findUser($email);
        $user_id = $user['id'];

        $db->query('INSERT INTO stat (task_id, assignee_id, status_id) VALUES (:task_id, :assignee_id, :status_id)', [
            'task_id' => $taskId,
            'assignee_id' => $user_id,
            'status_id' => 1,
        ]);
        $newStatus = 'Completed';
        echo json_encode(['status' => 'success', 'id' => $taskId, 'newStatus' => $newStatus]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid task ID']);
    }
}
