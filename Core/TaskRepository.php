<?php

namespace Core;

class TaskRepository
{
    public function createTask($data)
    {
        App::resolve(Database::class)->query('INSERT INTO tasks (title, creator_id, assignee_id) VALUES (:title, :creator_id, :assignee_id)', [
            'title' => $data['title'],
            'creator_id' => $data['creator_id'],
            'assignee_id' => $data['assignee_id']
        ]);
    }

    public function deleteTask($id)
    {
        App::resolve(Database::class)->query('DELETE FROM tasks WHERE id = :id', [
            'id' => $id
        ]);
    }

    public function editTask($id)
    {
        $task = App::resolve(Database::class)->query('select * from tasks where id = :id', [
            'id' => $id
        ])->findOrFail();

        return $task;
    }

    public function updateTask($data)
    {
        App::resolve(Database::class)->query('update tasks set title = :title where id = :id', [
            'id' => $data['id'],
            'title' => $data['title'],
        ]);
    }
}