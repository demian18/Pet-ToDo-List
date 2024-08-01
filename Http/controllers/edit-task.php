<?php

use Core\Repository\TaskRepository;

$id = $_GET['id'];

$taskRepository = new TaskRepository();

$task = $taskRepository->editTask((int)$id);

view("edit.view.php", [
    'task' => $task
]);