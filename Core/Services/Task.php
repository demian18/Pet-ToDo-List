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

    public function create($data): void
    {
        $this->taskRepo->createTask($data);
    }
}