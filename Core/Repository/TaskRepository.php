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
        return Task::select('tasks.id as task_id', 'tasks.title', 'tasks.body', 'tasks.status_id', 'tasks.assignee_id',
            'tasks.creator_id', 'status.status as status_name')
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
            'assignee_id' => $data['assignee']
        ]);
    }

    public function updateStatus($taskId): void
    {
        Task::where('id', $taskId)->update([
            'status_id' => 2
        ]);
    }

    public function get_all($user_id)
    {
        return Task::select('tasks.id as task_id', 'tasks.title', 'tasks.body', 'tasks.status_id', 'tasks.assignee_id',
            'tasks.creator_id', 'status.status as status_name')
            ->leftJoin('status', 'status.id', '=', 'tasks.status_id')
            ->where('tasks.assignee_id', $user_id)
            ->get();
    }

    public function get_completed($status, $user_id)
    {
        return Task::select('tasks.id as task_id', 'tasks.title', 'tasks.body', 'tasks.status_id', 'tasks.assignee_id',
            'tasks.creator_id', 'status.status as status_name')
            ->leftJoin('status', 'status.id', '=', 'tasks.status_id')
            ->where('status.status', $status)
            ->where('tasks.assignee_id', $user_id)
            ->get();
    }

    public function get_help($status, $user_id)
    {
        return Task::select('tasks.id as task_id', 'tasks.title', 'tasks.body', 'tasks.status_id', 'tasks.assignee_id',
            'tasks.creator_id', 'status.status as status_name')
            ->leftJoin('status', 'status.id', '=', 'tasks.status_id')
            ->where('status.status', $status)
            ->where('tasks.assignee_id', $user_id)
            ->get();
    }

    public function get_canceled($status, $user_id)
    {
        return Task::select('tasks.id as task_id', 'tasks.title', 'tasks.body', 'tasks.status_id', 'tasks.assignee_id',
            'tasks.creator_id', 'status.status as status_name')
            ->leftJoin('status', 'status.id', '=', 'tasks.status_id')
            ->where('status.status', $status)
            ->where('tasks.assignee_id', $user_id)
            ->get();
    }

    public function get_task_admin($user_id)
    {
        return Task::select(
            'tasks.id as task_id',
            'tasks.title as task_title',
            'status.status as task_status',
            'users.email as user_email'
        )
            ->join('users', 'tasks.assignee_id', '=', 'users.id')
            ->join('status', 'tasks.status_id', '=', 'status.id')
            ->where('tasks.creator_id', $user_id)
            ->get();
    }

    public function get_task_assignee($taskId)
    {
        return Task::select('status_id', 'assignee_id')
            ->where('id', $taskId)
            ->first();
    }

    public function update_status_canceled($taskId): void
    {
        Task::where('id', $taskId)->update(['status_id' => 3]);
    }

    public function get_status_task($taskId)
    {
        return Task::select('status_id')
            ->where('id', $taskId)
            ->first();
    }
}