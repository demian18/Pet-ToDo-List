<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

$task = $db->query('select * from todo where id = :id', [
    'id' => $_GET['id']
])->findOrFail();

view("edit.view.php", [
    'task' => $task
]);