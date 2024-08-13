<?php

namespace Core\Repository;

use Models\Task;

class TaskRepository
{
    public function getTasksAdmin($user_id)
    {
        return Task::where('creator_id', $user_id)->get();
    }

    public function getTasksWorker($user_id)
    {
        return Task::select('tasks.id as task_id', 'tasks.title', 'tasks.body', 'tasks.status_id', 'tasks.assignee_id', 'tasks.creator_id')
            ->leftJoin('status', 'status.id', '=', 'tasks.status_id')
            ->where('tasks.assignee_id', $user_id)
            ->get();
    }

    public function createTask($data): void
    {
        Task::create([
            'title' => $data['title'],
            'creator_id' => $data['creator_id'],
            'assignee_id' => $data['assignee_id']
        ]);
    }

    public function deleteTask($id): void
    {
        Task::where('id', $id)->delete();
    }

    public function editTask($id)
    {
        return Task::where('id', $id)->first();
    }

    public function updateTask($data): void
    {
        Task::where('id', $data['id'])->update([
            'title' => $data['title'],
        ]);
    }

    public function updateStatus($taskId): void
    {
        Task::where('id', $taskId)->update([
            'status_id' => 2
        ]);
    }
}