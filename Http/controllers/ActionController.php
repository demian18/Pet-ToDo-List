<?php

namespace Http\controllers;

use Core\App;
use Core\Repository\ActionRepository;
use Core\Repository\NotificationsRepository;
use Core\Repository\StatRepository;
use Core\Repository\UserRepository;
use Core\Services\Action;
use Core\Services\Notifications;
use Core\Services\Stat;
use Core\Services\User;
use Core\Session;

class ActionController
{
    private User $userService;
    private Action $actionService;

    public function __construct()
    {
        $this->userService = new User(App::resolve(UserRepository::class));
        $this->actionService = new Action(App::resolve(ActionRepository::class));
    }

    private function getUserFromSession(): ?\Models\User
    {
        $session_user = Session::get('user');
        $email = $session_user['email'];
        return $this->userService->findByEmail($email);
    }

    public function task_handl(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $taskId = $input['id'];

            if ($taskId) {
                $this->actionService->update_task_status($taskId);

                $user = $this->getUserFromSession();
                $user_id = $user->id;

                $statService = new Stat(App::resolve(StatRepository::class));
                $statService->new_stat($taskId, $user_id);

                echo json_encode(['status' => 'success', 'message' => 'perform', 'id' => $taskId]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid task ID']);
            }
        }
    }

    public function help_task(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $taskId = $input['id'];

            if ($taskId) {
                $creator = $this->actionService->find_creator_task($taskId);
                $creator_id = $creator['creator_id'];

                $user = $this->getUserFromSession();
                $user_id = $user->id;

                $notService = new Notifications(App::resolve(NotificationsRepository::class));
                $notifications = $notService->get_not_id($taskId);

                if (empty($notifications)) {
                    $notService->create_not($taskId, $user_id, $creator_id);

                    $statService = new Stat(App::resolve(StatRepository::class));
                    $statService->status_help($taskId);

                    echo json_encode(['status' => 'success', 'message' => 'help', 'id' => $taskId, 'creator_notifications' => $user_id]);
                } else {
                    echo json_encode(['status' => 'success', 'message' => 'notification already exists']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid task ID']);
            }
        }
    }

    public function cancel_task(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $taskId = $input['id'];

            if ($taskId) {
                $task = $this->actionService->select_status_task($taskId);

                if (in_array($task['status_id'], [2, 4])) {
                    $this->actionService->status_cancel($taskId);

                    $user = $this->getUserFromSession();
                    $user_id = $user->id;

                    $statService = new Stat(App::resolve(StatRepository::class));
                    $statService->status_cancel($taskId, $user_id);

                    echo json_encode([
                        'status' => 'success',
                        'message' => 'cancel',
                        'id' => $taskId,
                        'newStatus' => 'Cancelled'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'false',
                        'message' => 'This task cannot be canceled',
                        'id' => $taskId,
                    ]);
                }

            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid task ID']);
            }
        }
    }
}