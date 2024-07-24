<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $status = $input['status'];
    $email = $_SESSION['user']['email'];

    $user = $db->query('SELECT id FROM users WHERE email = :email', [
        'email' => $email
    ])->findOrFail();
    $user_id = $user['id'];

    if ($status === 'all') {
        $tasks = $db->query('SELECT tasks.id AS task_id, tasks.title, tasks.body, tasks.status_id, tasks.assignee_id, tasks.creator_id,
            status.status AS status_name
            FROM tasks LEFT JOIN status ON status.id = tasks.status_id where tasks.assignee_id = :assignee_id', [
            'assignee_id' => $user_id
        ])->get();
    } elseif ($status === 'completed') {
        $tasks = $db->query('SELECT tasks.id AS task_id, tasks.title, tasks.body, tasks.status_id, tasks.assignee_id, tasks.creator_id,
            status.status AS status_name
            FROM tasks 
            LEFT JOIN status ON status.id = tasks.status_id 
            WHERE status.status = :status AND tasks.assignee_id = :assignee_id', [
            'status' => $status,
            'assignee_id' => $user_id
        ])->get();
    } elseif ($status === 'help') {
        $tasks = $db->query('SELECT tasks.id AS task_id, tasks.title, tasks.body, tasks.status_id, tasks.assignee_id, tasks.creator_id,
            status.status AS status_name
            FROM tasks 
            LEFT JOIN status ON status.id = tasks.status_id 
            WHERE status.status = :status AND tasks.assignee_id = :assignee_id', [
            'status' => $status,
            'assignee_id' => $user_id
        ])->get();
    }

    ob_start();
    foreach ($tasks as $task) {
        view("task_template.view.php", ['task' => $task]);
    }
    $tasksHtml = ob_get_clean();

    echo json_encode(['tasksHtml' => $tasksHtml]);
}
