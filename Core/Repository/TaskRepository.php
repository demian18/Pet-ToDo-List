<?php

namespace Core\Repository;

use Core\App;
use Core\Database;

class TaskRepository
{
    protected Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getTasksAdmin($user_id)
    {
        return $this->db->query('SELECT * FROM tasks WHERE creator_id = :creator_id', [
            'creator_id' => $user_id
        ])->get();
    }

    public function getTasksWorker($user_id)
    {
        return $this->db->query('SELECT tasks.id AS task_id, tasks.title, tasks.body, tasks.status_id, tasks.assignee_id, tasks.creator_id,
            status.status AS status_name
            FROM tasks LEFT JOIN status ON status.id = tasks.status_id where tasks.assignee_id = :assignee_id', [
            'assignee_id' => $user_id
        ])->get();
    }

    public function createTask($data): void
    {
        $this->db->query('INSERT INTO tasks (title, creator_id, assignee_id) VALUES (:title, :creator_id, :assignee_id)', [
            'title' => $data['title'],
            'creator_id' => $data['creator_id'],
            'assignee_id' => $data['assignee_id']
        ]);
    }

    public function deleteTask($id): void
    {
        $this->db->query('DELETE FROM tasks WHERE id = :id', [
            'id' => $id
        ]);
    }

    public function editTask($id)
    {
        $task = $this->db->query('SELECT * FROM tasks WHERE id = :id', [
            'id' => $id
        ])->findOrFail();

        return $task;
    }

    public function updateTask($data): void
    {
        $this->db->query('UPDATE tasks SET title = :title WHERE id = :id', [
            'id' => $data['id'],
            'title' => $data['title'],
        ]);
    }
}