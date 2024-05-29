<?php

$config = require ('config.php');
$db = new Database($config['database']);
$todoList = $db->query('select * from todo')->fetchAll(PDO::FETCH_ASSOC);
require 'views/index.view.php';