<?php

namespace Core\Repository;

use Core\Database;

class NotificationsRepository
{
    protected Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function get_not_id($taskId)
    {
        return $this->db->query('SELECT id FROM notifications WHERE task_id = :task_id', [
            'task_id' => $taskId
        ])->get();
    }

    public function create($taskId, $user_id, $creator_id): void
    {
        $this->db->query('INSERT INTO notifications (task_id, creator_id, assignee_id)
                    VALUES (:task_id, :creator_id, :assignee_id)', [
            'task_id' => $taskId,
            'creator_id' => $user_id,
            'assignee_id' => $creator_id,
        ]);
    }
}