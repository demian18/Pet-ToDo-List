<?php

use Core\App;
use Core\Database;
use Core\Validator;

$db = App::resolve(Database::class);

$task = $db->query('select * from todo where id = :id', [
    'id' => $_POST['id']
])->find();

$errors = [];

if (!Validator::string($_POST['title'], 5, 25)) {
    $errors['title'] = 'Title must be between 5 and 25 characters!';
}

if (count($errors)) {
    return view("edit.view.php", [
        'errors' => $errors,
        'task' => $task
    ]);
}
$db->query('update todo set title = :title where id = :id', [
    'id' => $_POST['id'],
    'title' => $_POST['title'],
]);
header('Location: /');
exit();