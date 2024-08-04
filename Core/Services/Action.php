<?php

namespace Core\Services;

use Core\Repository\ActionRepository;

class Action
{
    protected ActionRepository $actionRepo;

    public function __construct(ActionRepository $actionRepo)
    {
        $this->actionRepo = $actionRepo;
    }

    public function update_task_status($taskId): void
    {
        $this->actionRepo->update_task_status($taskId);
    }

    public function find_creator_task($taskId)
    {
        return $this->actionRepo->find_creator_task($taskId);
    }

    public function select_status_task($taskId)
    {
        return $this->actionRepo->select_status($taskId);
    }

    public function status_cancel($taskId): void
    {
        $this->actionRepo->status_cancel($taskId);
    }
}