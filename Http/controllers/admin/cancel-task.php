<?php

use Core\App;
use Core\Database;
use Core\Session;

$taskId = $_POST['id'];

$db = App::resolve(Database::class);

$session_user = Session::get('user');
$email = $session_user['email'];

$task = $db->query('SELECT status_id, assignee_id FROM tasks WHERE id = :id', [
    'id' => $taskId
])->findOrFail();

if ($task['status_id'] != 3) {
    $db->query('UPDATE tasks SET status_id = :status_id WHERE id = :task_id', [
        'status_id' => 3,
        'task_id' => $taskId,
    ]);
    $updatedTask = $db->query('SELECT status_id FROM tasks WHERE id = :id', [
        'id' => $taskId
    ])->findOrFail();
    if ($updatedTask['status_id'] == 1) {
        $db->query('UPDATE stat SET status_id = :status_id WHERE id = :task_id', [
            'status_id' => 3,
            'task_id' => $taskId,
        ]);
    }
}
header('Location:/tasks');
exit();