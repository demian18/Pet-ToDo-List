<?php

use Core\Repository\TaskRepository;

$id = $_POST['id'];

$taskRepository = new TaskRepository();
$taskRepository->deleteTask((int)$id);

header('Location: /');
exit();