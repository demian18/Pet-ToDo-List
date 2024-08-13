<?php

namespace Core\Services;

use Core\Repository\TaskRepository;

class Task
{
    protected TaskRepository $taskRepo;

    public function __construct(TaskRepository $taskRepo)
    {
        $this->taskRepo = $taskRepo;
    }

    public function getTasksAdmin($user_id)
    {
        return $this->taskRepo->getTasksAdmin($user_id);
    }

    public function getTasksWorker($user_id)
    {
        return $this->taskRepo->getTasksWorker($user_id);
    }

    public function create($data): void
    {
        $this->taskRepo->createTask($data);
    }

    public function edit($id)
    {
        return $this->taskRepo->editTask($id);
    }

    public function update($data): void
    {
        $this->taskRepo->updateTask($data);
    }

    public function delete($id): void
    {
        $this->taskRepo->deleteTask($id);
    }

    public function updateStatus($taskId): void
    {
        $this->taskRepo->updateStatus($taskId);
    }

    public function get_all($user_id)
    {
        return $this->taskRepo->get_all($user_id);
    }

    public function get_completed($status, $user_id)
    {
        return $this->taskRepo->get_completed($status, $user_id);
    }

    public function get_help($status, $user_id)
    {
        return $this->taskRepo->get_help($status, $user_id);
    }

    public function get_canceled($status, $user_id)
    {
        return $this->taskRepo->get_canceled($status, $user_id);
    }
}