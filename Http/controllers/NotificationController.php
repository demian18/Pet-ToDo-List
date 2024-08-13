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
    public function index()
    {
        $session_user = Session::get('user');
        $email = $session_user['email'];

        $userService = new User(App::resolve(UserRepository::class));
        $user = $userService->findUser($email);
        $user_id = $user->id;

        $notService = new Notifications(App::resolve(NotificationsRepository::class));

        $notifications = $notService->get_notifications($user_id);

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

        $notService = new Notifications(App::resolve(NotificationsRepository::class));
        $notService->update_not_status_complete($taskId);

        $notService = new Task(App::resolve(TaskRepository::class));
        $notService->updateStatus($taskId);

        header('location: /notifications');
        exit();
    }
}