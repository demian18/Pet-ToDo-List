<?php

namespace Core\Services;

use Core\Repository\NotificationsRepository;

class Notifications
{
    protected NotificationsRepository $notRepo;

    public function __construct(NotificationsRepository $notRepo)
    {
        $this->notRepo = $notRepo;
    }

    public function get_not_id($taskId)
    {
        return $this->notRepo->get_not_id($taskId);
    }

    public function create_not($taskId, $user_id, $creator_id): void
    {
        $this->notRepo->create($taskId, $user_id, $creator_id);
    }
}