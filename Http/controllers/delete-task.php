<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

$id = $_POST['id'];

$db->query('DELETE FROM todo WHERE id = :id', [
    'id' => $id
]);

header('Location: /');
exit();