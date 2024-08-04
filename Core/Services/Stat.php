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
}