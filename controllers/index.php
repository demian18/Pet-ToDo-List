<?php

$config = require ('config.php');
$db = new Database($config['database']);
$tasks = $db->query('select * from todo where user_id = 1')->fetchAll(PDO::FETCH_ASSOC);
require 'views/index.view.php';