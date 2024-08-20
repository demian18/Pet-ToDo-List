<?php

namespace Core\Repository;

use Models\Comment;

class CommentRepository
{
    public function get_comments(array $notification_ids)
    {
        return Comment::whereIn('not_id', $notification_ids)->get();
    }

    public function create($comment, $not_id, $task_id, $user_id, $assignee_id): void
    {
        Comment::create([
            'comment' => $comment,
            'not_id' => $not_id,
            'task_id' => $task_id,
            'creator_id' => $user_id,
            'assignee_id' => $assignee_id
        ]);
    }
}