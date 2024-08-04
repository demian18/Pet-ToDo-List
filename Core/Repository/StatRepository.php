<?php

namespace Core\Repository;

use Core\App;
use Core\Database;

class StatRepository
{
    protected $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }
    public function finishedTask($user_id)
    {
        return $this->db->query('SELECT id, status_id FROM stat WHERE assignee_id = :assignee_id', [
            'assignee_id' => $user_id
        ])->get();
    }
}