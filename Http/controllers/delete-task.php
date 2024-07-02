<?php

use Core\TaskRepository;

$id = $_POST['id'];

$taskRepository = new TaskRepository();
$taskRepository->deleteTask((int)$id);

header('Location: /');
exit();