<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $taskId = $input['id'];

    if ($taskId) {
        $task = $db->query('UPDATE tasks SET status_id = 1 WHERE id = :id', [
            'id' => $taskId
        ]);
        $newStatus = 'Completed';
        echo json_encode(['status' => 'success', 'id' => $taskId, 'newStatus' => $newStatus]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid task ID']);
    }
}
