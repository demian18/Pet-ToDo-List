<?php

namespace Http\controllers;

use Core\App;
use Core\Repository\NotificationsRepository;
use Core\Repository\TaskRepository;
use Core\Repository\UserRepository;
use Core\Request;
use Core\Services\Notifications;
use Core\Services\Task;
use Core\Services\User;
use Core\Session;

class NotificationController
{
    private User $userService;
    private Notifications $notService;
    private Task $taskService;
    private Request $request;

    public function __construct(User $userService, Notifications $notService, Task $taskService, Request $request)
    {
        $this->userService = $userService;
        $this->notService = $notService;
        $this->taskService = $taskService;
        $this->request = $request;
    }

    private function getUserFromSession(): ?\Models\User
    {
        $session_user = Session::get('user');
        $email = $session_user['email'];
        return $this->userService->findByEmail($email);
    }

    public function index()
    {
        $user = $this->getUserFromSession();
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
        $taskId = $this->request->post('task_id');

        $this->notService->update_not_status_complete($taskId);

        $this->taskService->updateStatus($taskId);

        header('location: /notifications');
        exit();
    }

    public function getNotificationCount(): void
    {
        $user = $this->getUserFromSession();
        $user_id = $user->id;

        $notification_count = $this->notService->get_count_not($user_id);

        header('Content-Type: application/json');
        echo json_encode([
            'count' => $notification_count,
        ]);
        exit();
    }
}