<?php

namespace Http\controllers;

use Core\App;
use Core\Repository\NotificationsRepository;
use Core\Repository\TaskRepository;
use Core\Repository\UserRepository;
use Core\Services\Notifications;
use Core\Services\Task;
use Core\Services\User;
use Core\Session;

class NotificationController
{
    private User $userService;
    private Notifications $notService;
    private Task $taskService;

    public function __construct(User $userService, Notifications $notService, Task $taskService)
    {
        $this->userService = $userService;
        $this->notService = $notService;
        $this->taskService = $taskService;
    }

    public function index()
    {
        $session_user = Session::get('user');
        $email = $session_user['email'];

        $user = $this->userService->findUser($email);
        $user_id = $user->id;

        $notifications = $this->notService->get_notifications($user_id);

        $unique_notifications = [];

        foreach ($notifications as $notification) {
            $task_id = $notification['task_id'];
            $type = $notification['type'];

            if (isset($unique_notifications[$task_id]) && $type == 'comment') {
                continue;
            }

            $unique_notifications[$task_id] = $notification;
        }

        $notifications_to_display = array_values($unique_notifications);

        view('notifications/index.view.php', [
            'notifications' => $notifications_to_display
        ]);
    }

    public function close()
    {
        $taskId = $_POST['task_id'];

        $this->notService->update_not_status_complete($taskId);

        $this->taskService->updateStatus($taskId);

        header('location: /notifications');
        exit();
    }
}