<?php

namespace Core\Repository;

use Core\App;
use Core\Database;

class StatRepository
{
    public function finishedTask($user_id)
    {
        $stat_tasks = App::resolve(Database::class)->query('SELECT id, status_id FROM stat WHERE assignee_id = :assignee_id', [
            'assignee_id' => $user_id
        ])->get();
        return $stat_tasks;
    }
}