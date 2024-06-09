<?php
use Core\Database;

$config = require base_path('config.php');
$db = new Database($config['database']);

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id = $_POST['id'];

    $db->query('DELETE FROM todo WHERE id = :id', [
        'id' => $id
    ]);

    header('Location: /');
    exit();
}