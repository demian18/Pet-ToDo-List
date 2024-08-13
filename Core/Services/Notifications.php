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

    public function get_count_not($user_id)
    {
        return $this->notRepo->get_count($user_id);
    }

    public function get_notifications($user_id)
    {
        return $this->notRepo->get_not($user_id);
    }

    public function get_not_comments($taskId)
    {
        return $this->notRepo->get_not_comments($taskId);
    }

    public function update_not_com($notification): void
    {
        $this->notRepo->update_not_com($notification);
    }

    public function get_not_creator_assignee($not_id)
    {
        return $this->notRepo->get_not_creator_assignee($not_id);
    }

    public function create_not_comment($task_id, $user_id, $assignee_id): void
    {
        $this->notRepo->create_not_comment($task_id, $user_id, $assignee_id);
    }

    public function update_not_status_complete($taskId): void
    {
        $this->notRepo->status_complete($taskId);
    }
}