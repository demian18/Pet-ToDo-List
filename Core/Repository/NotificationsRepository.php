<?php

namespace Core\Repository;

use Models\Notification;

class NotificationsRepository
{
    public function get_not_id($taskId)
    {
        return Notification::select('id')->where('task_id', $taskId)->first();
    }

    public function create($taskId, $user_id, $creator_id): void
    {
        Notification::create([
            'task_id' => $taskId,
            'creator_id' => $user_id,
            'assignee_id' => $creator_id,
        ]);
    }

    public function get_count($user_id)
    {
        return Notification::where('assignee_id', $user_id)
            ->where('status', 'new')
            ->count();
    }

    public function get_not($user_id)
    {
        return Notification::select('notifications.id', 'notifications.task_id', 'notifications.assignee_id', 'users.email',
            'notifications.type', 'notifications.status', 'notifications.created_at')
            ->join('users', 'notifications.creator_id', '=', 'users.id')
            ->where('notifications.assignee_id', $user_id)
            ->where('notifications.status', '!=', 'completed')
            ->get();
    }

    public function get_not_comments($taskId)
    {
        return Notification::select('id', 'assignee_id', 'type')
            ->where('task_id', $taskId)
            ->get();
    }

    public function update_not_com($notification): void
    {
        Notification::where('id', $notification['id'])->update(['status' => 'viewed']);
    }

    public function get_not_creator_assignee($not_id)
    {
        return Notification::select('assignee_id', 'creator_id')
            ->where('id', $not_id)
            ->first();
    }

    public function create_not_comment($task_id, $user_id, $assignee_id): void
    {
        Notification::create([
            'task_id' => $task_id,
            'creator_id' => $user_id,
            'assignee_id' => $assignee_id,
            'type' => 'comment',
            'status' => 'new'
        ]);
    }

    public function status_complete($taskId): void
    {
        Notification::where('task_id', $taskId)->update([
            'status' => 'completed'
        ]);
    }
}