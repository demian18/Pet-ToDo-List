<?php

namespace Core\Repository;

use Core\Database;
use Models\Task;

class ActionRepository
{
    public function update_task_status($taskId): void
    {
        Task::where('id', $taskId)->update([
            'status_id' => 1
        ]);
    }

    public function find_creator_task($taskId)
    {
        return Task::select('creator_id')->where('id', $taskId)->first();
    }

    public function select_status($taskId)
    {
        return Task::select('status_id')->where('id', $taskId)->first();
    }

    public function status_cancel($taskId): void
    {
        Task::where('id', $taskId)->update([
            'status_id' => 3
        ]);
    }
}