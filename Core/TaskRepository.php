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
}