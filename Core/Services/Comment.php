<?php

namespace Core\Services;

use Core\Repository\CommentRepository;

class Comment
{
    protected CommentRepository $comRepo;

    public function __construct(CommentRepository $comRepo)
    {
        $this->comRepo = $comRepo;
    }
    public function get_comments($notification_ids)
    {
        return $this->comRepo->get_comments($notification_ids);
    }

    public function create_comment($comment, $not_id, $task_id, $user_id, $assignee_id): void
    {
        $this->comRepo->create($comment, $not_id, $task_id, $user_id, $assignee_id);
    }
}