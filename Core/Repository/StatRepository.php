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

    public function new_stat($taskId, $user_id): void
    {
        $this->db->query('INSERT INTO stat (task_id, assignee_id, status_id) VALUES (:task_id, :assignee_id, :status_id)', [
            'task_id' => $taskId,
            'assignee_id' => $user_id,
            'status_id' => 1,
        ]);
    }

    public function status_help($taskId): void
    {
        $this->db->query('UPDATE tasks set status_id = 4 where id = :id', [
            'id' => $taskId,
        ]);
    }

    public function status_cancel($taskId, $user_id): void
    {
        $this->db->query('INSERT INTO stat (task_id, assignee_id, status_id) VALUES (:task_id, :assignee_id, :status_id)', [
            'task_id' => $taskId,
            'assignee_id' => $user_id,
            'status_id' => 3,
        ]);
    }
}