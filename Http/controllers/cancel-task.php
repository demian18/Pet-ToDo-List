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
        $task = $db->query('SELECT status_id FROM tasks WHERE id = :id', [
            'id' => $taskId
        ])->findOrFail();

        if (in_array($task['status_id'], [2, 4])) {
            $db->query('UPDATE tasks SET status_id = :status_id WHERE id = :task_id', [
                'status_id' => 3,
                'task_id' => $taskId,
            ]);
            $profileRepository = new ProfileRepository();

            $user = $profileRepository->findUser($email);
            $user_id = $user['id'];

            $db->query('INSERT INTO stat (task_id, assignee_id, status_id) VALUES (:task_id, :assignee_id, :status_id)', [
                'task_id' => $taskId,
                'assignee_id' => $user_id,
                'status_id' => 3,
            ]);

            echo json_encode([
                'status' => 'success',
                'message' => 'cancel',
                'id' => $taskId,
                'newStatus' => 'Cancelled'
            ]);
        } else {
            echo json_encode([
                'status' => 'false',
                'message' => 'This task cannot be canceled',
                'id' => $taskId,
            ]);
        }

    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid task ID']);
    }
}
