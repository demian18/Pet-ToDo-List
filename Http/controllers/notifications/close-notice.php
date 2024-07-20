<?php

use Core\App;
use Core\Database;

$taskId = $_POST['task_id'];

$db = App::resolve(Database::class);

$db->query('UPDATE notifications SET status = "completed" WHERE task_id = :task_id', [
    'task_id' => $taskId
]);

$db->query('UPDATE tasks set status_id = 2 where id = :id', [
    'id' => $taskId,
]);

header('location: /notifications');
exit();