<?php

use Core\TaskRepository;
use Http\Forms\TaskForm;

$taskRepository = new TaskRepository();

$id = $_POST['id'];
$task = $taskRepository->editTask($id);

$form = new TaskForm($_POST);
if (!$form->validate()){
    return view("edit.view.php", [
        'errors' => $form->errors(),
        'task' => $task
    ]);
}
$taskRepository->updateTask($_POST);

header('Location: /');
exit();