<?php

namespace Core\Repository;

use Exception;
use Models\Stat;
use Models\Task;

class StatRepository
{
    public function finishedTask($user_id)
    {
        return Stat::select('id', 'status_id')
            ->where('assignee_id', $user_id)
            ->get();
    }

    public function new_stat($taskId, $user_id): void
    {
        Stat::create([
            'task_id' => $taskId,
            'assignee_id' => $user_id,
            'status_id' => 1,
        ]);
    }

    public function status_cancel($taskId, $user_id): void
    {
        Stat::create([
            'task_id' => $taskId,
            'assignee_id' => $user_id,
            'status_id' => 3,
        ]);
    }

    public function status_help($taskId): void
    {
        Task::where('id', $taskId)->update(['status_id' => 4]);
    }

    public function update_stat_cancel($taskId): void
    {
        Stat::where('id', $taskId)->update(['status_id' => 3]);
    }

    public function getCountByStatus($status_id) {
        try {
            if (!in_array($status_id, [1, 3])) {
                throw new Exception('Invalid parameters');
            }
            return Stat::where('status_id', $status_id)->count();
        } catch (\Exception $e) {
            throw new \Exception('Error when executing a database query: ' . $e->getMessage());
        }
    }

    public function getCountByUser($user_id, $status_id) {
        try {
            if (!is_numeric($user_id) || !in_array($status_id, [1, 3])) {
                throw new Exception('Invalid parameters');
            }
            return Stat::where('status_id', $status_id)->where('assignee_id', $user_id)->count();
        } catch (\Exception $e) {
            throw new \Exception('Error when executing a database query: ' . $e->getMessage());
        }
    }

    public function getCountByStatusInPeriod($status_id, $start, $end)
    {
        try {
            if (!in_array($status_id, [1, 3])) {
                throw new Exception('Invalid parameters');
            }
            return Stat::whereBetween('timestamp', [$start, $end])->where('status_id', $status_id)->count();
        } catch (\Exception $e) {
            throw new \Exception('Error when executing a database query: ' . $e->getMessage());
        }
    }
}