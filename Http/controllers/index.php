<?php

use Core\Database;
use Core\App;
use Core\Session;

$errors = [];
if (isset($_GET['errors'])) {
    $errors = json_decode($_GET['errors'], true);
}

$db = App::resolve(Database::class);

$session_user = Session::get('user');

if ($session_user == null) {
    view("index.view.php");
    exit();
} else {
    $email = $session_user['email'];

    $user = $db->query('SELECT id, role_id FROM users WHERE email = :email', [
        'email' => $email
    ])->findOrFail();

    $user_id = $user['id'];
    $role = $user['role_id'];

    if ($role == 2) { // Admin
        $users = $db->query('SELECT id, email FROM users WHERE role_id != 2')->get();

        $tasks = $db->query('select * from tasks where creator_id = :creator_id', [
            'creator_id' => $user_id
        ])->get();

        view("index.view.php", [
            'errors' => $errors,
            'tasks' => $tasks,
            'role' => $role,
            'users' => $users
        ]);
    } elseif ($role == 1) { // Worker
        $tasks = $db->query('SELECT tasks.id AS task_id, tasks.title, tasks.body, tasks.status_id, tasks.assignee_id, tasks.creator_id,
            status.status AS status_name
            FROM tasks LEFT JOIN status ON status.id = tasks.status_id where tasks.assignee_id = :assignee_id', [
            'assignee_id' => $user_id
        ])->get();

        view("index.view.php", [
            'errors' => $errors,
            'tasks' => $tasks,
            'role' => $role,
        ]);
    }
}