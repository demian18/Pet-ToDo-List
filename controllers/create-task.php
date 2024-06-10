<?php

use Core\App;
use Core\Database;
use Core\Validator;

$db = App::resolve(Database::class);

$errors = [];


if (!Validator::string($_POST['title'], 5, 25)) {
    $errors['title'] = 'Title must be between 5 and 25 characters!';
}

if (!empty($errors)) {
    $errorParams = http_build_query(['errors' => json_encode($errors)]);
    header("Location: /?$errorParams");
    exit();
}

$db->query('INSERT INTO todo (title, user_id) VALUES (:title, :user_id)', [
    'title' => $_POST['title'],
    'user_id' => 1,
]);
header('Location: /');
exit();