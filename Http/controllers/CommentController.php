<?php

namespace Http\controllers;

use Core\App;
use Core\Repository\CommentRepository;
use Core\Repository\NotificationsRepository;
use Core\Repository\UserRepository;
use Core\Services\Comment;
use Core\Services\Notifications;
use Core\Services\User;
use Core\Session;

class CommentController
{
    private User $userService;

    public function __construct()
    {
        $this->userService = new User(App::resolve(UserRepository::class));
    }

    private function getUserFromSession(): ?\Models\User
    {
        $session_user = Session::get('user');
        $email = $session_user['email'];
        return $this->userService->findByEmail($email);
    }

    public function index()
    {
        $taskId = $_GET['id'];

        $user = $this->getUserFromSession();
        $user_id = $user->id;

        $notService = new Notifications(App::resolve(NotificationsRepository::class));
        $notifications = $notService->get_not_comments($taskId);

        $notification_ids = $notifications->pluck('id')->toArray();

        if (empty($notification_ids)) {
            $comments = [];
            $notId = null;
        } else {
            $comService = new Comment(App::resolve(CommentRepository::class));
            $comments = $comService->get_comments($notification_ids);
            $notId = $notification_ids[0];
        }

        $assignee_ids = $notifications->pluck('assignee_id')->toArray();

        if (in_array($user_id, $assignee_ids)) {
            foreach ($notifications as $notification) {
                if ($notification['assignee_id'] == $user_id) {
                    $notService->update_not_com($notification);
                }
            }
        }

        view('comments/index.view.php', [
            'taskId' => $taskId,
            'notId' => $notId,
            'comments' => $comments,
        ]);
    }

    public function create()
    {
        $comment = $_POST['comment'];
        $task_id = $_POST['task_id'];
        $not_id = $_POST['not_id'];

        $user = $this->getUserFromSession();

        $user_id = $user->id;
        $user_role = $user->role_id;

        $notService = new Notifications(App::resolve(NotificationsRepository::class));
        $notification = $notService->get_not_creator_assignee($not_id);

        if ($user_role == 2) {
            $assignee_id = $notification->creator_id;
        } else {
            $assignee_id = $notification->assignee_id;
        }

        $comService = new Comment(App::resolve(CommentRepository::class));
        $comService->create_comment($comment, $not_id, $task_id, $user_id, $assignee_id);

        $notService->create_not_comment($task_id, $user_id, $assignee_id);

        header('Location: /notifications');
        exit();
    }
}