<?php

use Core\App;
use Core\Database;
use Cron\CronExpression;

require_once realpath(__DIR__ . '/../../public/init.php');

$db = App::resolve(Database::class);

$cron = new CronExpression('* * * * *');
if ($cron->isDue()) {

    $notifications = $db->query('SELECT * FROM notifications WHERE processed = 0')->get();

    if (!empty($notifications)) {

        $db->query('UPDATE notifications SET processed = 1 WHERE processed = 0');
    }
}
