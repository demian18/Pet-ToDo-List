<?php

namespace Core;

class TaskRepository
{
    public function createTask($data)
    {
        App::resolve(Database::class)->query('INSERT INTO todo (title, user_id) VALUES (:title, :user_id)', [
            'title' => $data['title'],
            'user_id' => $data['user_id'],
        ]);
    }

    public function deleteTask($id)
    {
        App::resolve(Database::class)->query('DELETE FROM todo WHERE id = :id', [
            'id' => $id
        ]);
    }

    public function editTask($id)
    {
        $task = App::resolve(Database::class)->query('select * from todo where id = :id', [
            'id' => $id
        ])->findOrFail();

        return $task;
    }

    public function updateTask($data)
    {
        App::resolve(Database::class)->query('update todo set title = :title where id = :id', [
            'id' => $data['id'],
            'title' => $data['title'],
        ]);
    }
}