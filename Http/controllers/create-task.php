<?php

use Core\TaskRepository;
use Http\Forms\TaskForm;

$form = new TaskForm($_POST);

if (!$form->validate()) {
    $errorParams = http_build_query(['errors' => json_encode($form->errors())]);
    header("Location: /?$errorParams");
    exit();
}

$taskRepository = new TaskRepository();
$taskRepository->createTask([
    'title' => $form->get('title'),
    'user_id' => 1
]);

header('Location: /');
exit();