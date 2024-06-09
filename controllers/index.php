<?php
$errors = [];
if (isset($_GET['errors'])) {
    $errors = json_decode($_GET['errors'], true);
}
$config = require base_path('config.php');
$db = new Database($config['database']);
$tasks = $db->query('select * from todo where user_id = 1')->fetchAll(PDO::FETCH_ASSOC);
require base_path('views/index.view.php');