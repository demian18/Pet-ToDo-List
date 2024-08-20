<?php

namespace Core\Services;

use Core\Repository\StatRepository;

class Stat
{
    protected StatRepository $statRepo;

    public function __construct(StatRepository $statRepo)
    {
        $this->statRepo = $statRepo;
    }

    public function finishedTask($user_id)
    {
        return $this->statRepo->finishedTask($user_id);
    }

    public function new_stat($taskId, $user_id): void
    {
        $this->statRepo->new_stat($taskId, $user_id);
    }

    public function status_help($taskId): void
    {
        $this->statRepo->status_help($taskId);
    }

    public function status_cancel($taskId, $user_id): void
    {
        $this->statRepo->status_cancel($taskId, $user_id);
    }

    public function update_status_cancel($taskId): void
    {
        $this->statRepo->update_stat_cancel($taskId);
    }
}