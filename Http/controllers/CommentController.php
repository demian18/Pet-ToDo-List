<?php

namespace Http\controllers;

use Core\Request;
use Core\Services\Comment;
use Core\Services\Notifications;
use Core\Services\User;
use Core\Session;

class CommentController
{
    private User $userService;
    private Notifications $notService;
    private Comment $comService;
    private Request $request;

    public function __construct(User $userService, Notifications $notService, Comment $comService, Request $request)
    {
        $this->userService = $userService;
        $this->notService = $notService;
        $this->comService = $comService;
        $this->request = $request;
    }

    private function getUserFromSession(): ?\Models\User
    {
        $session_user = Session::get('user');
        $email = $session_user['email'];
        return $this->userService->findByEmail($email);
    }

    public function index(): void
    {
        $taskId = $this->request->query('id');

        $user = $this->getUserFromSession();
        $user_id = $user->id;

        $notifications = $this->notService->get_not_comments($taskId);
        $notification_ids = $notifications->pluck('id');

        $comments = $notification_ids->isEmpty()
            ? collect()
            : $this->comService->get_comments($notification_ids->toArray());

        $notifications->where('assignee_id', $user_id)
            ->each(fn($notification) => $this->notService->update_not_com($notification));

        view('comments/index.view.php', [
            'taskId' => $taskId,
            'notId' => $notification_ids->first(),
            'comments' => $comments,
        ]);
    }

    public function create(): void
    {
        $comment = $this->request->post('comment');
        $task_id = $this->request->post('task_id');
        $not_id = $this->request->post('not_id');

        $user = $this->getUserFromSession();

        $user_id = $user->id;
        $user_role = $user->role_id;

        $notification = $this->notService->get_not_creator_assignee($not_id);

        $assignee_id = $user_role == 2 ? $notification->creator_id : $notification->assignee_id;

        $this->comService->create_comment($comment, $not_id, $task_id, $user_id, $assignee_id);
        $this->notService->create_not_comment($task_id, $user_id, $assignee_id);

        header('Location: /notifications');
        exit();
    }
}