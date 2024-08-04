<?php

namespace Core\Repository;

use Core\Database;

class ActionRepository
{
    protected Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function update_task_status($taskId): void
    {
        $this->db->query('UPDATE tasks SET status_id = 1 WHERE id = :id', [
            'id' => $taskId
        ]);
    }

    public function find_creator_task($taskId)
    {
        return $this->db->query('SELECT creator_id FROM tasks WHERE id = :id', [
            'id' => $taskId
        ])->findOrFail();
    }

    public function select_status($taskId)
    {
        return $this->db->query('SELECT status_id FROM tasks WHERE id = :id', [
            'id' => $taskId
        ])->findOrFail();
    }

    public function status_cancel($taskId): void
    {
        $this->db->query('UPDATE tasks SET status_id = :status_id WHERE id = :task_id', [
            'status_id' => 3,
            'task_id' => $taskId,
        ]);
    }
}