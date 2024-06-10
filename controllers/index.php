<?php
Use Core\Database;
Use Core\App;

$errors = [];
if (isset($_GET['errors'])) {
    $errors = json_decode($_GET['errors'], true);
}

$db = App::resolve(Database::class);

$tasks = $db->query('select * from todo where user_id = 1')->fetchAll(PDO::FETCH_ASSOC);
view("index.view.php", [
    'errors' => $errors,
    'tasks' => $tasks
]);