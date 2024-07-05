<?php

use Core\App;
use Core\Database;
use Core\Session;
use Core\TaskRepository;
use Http\Forms\TaskForm;

$form = new TaskForm($_POST);

if (!$form->validate()) {
    $errorParams = http_build_query(['errors' => json_encode($form->errors())]);
    header("Location: /?$errorParams");
    exit();
}
$session_user = Session::get('user');
$email = $session_user['email'];

$db = App::resolve(Database::class);

$user = $db->query('SELECT id FROM users WHERE email = :email', [
    'email' => $email
])->findOrFail();

$taskRepository = new TaskRepository();
$taskRepository->createTask([
    'title' => $form->get('title'),
    'assignee_id' => $form->get('assignee'),
    'creator_id' => $user['id'],
]);

header('Location: /');
exit();