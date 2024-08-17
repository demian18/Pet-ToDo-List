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
    private Stat $statService;
    private Notifications $notService;

    public function __construct()
    {
        $this->userService = new User(App::resolve(UserRepository::class));
        $this->actionService = new Action(App::resolve(ActionRepository::class));
        $this->statService = new Stat(App::resolve(StatRepository::class));
        $this->notService = new Notifications(App::resolve(NotificationsRepository::class));
    }

    private function getUserFromSession(): ?\Models\User
    {
        $session_user = Session::get('user');
        $email = $session_user['email'];
        return $this->userService->findByEmail($email);
    }

    private function getJsonInput(): array
    {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }

    private function validateTaskId(array $input): int
    {
        if (empty($input['id'])) {
            throw new \InvalidArgumentException('Invalid task ID');
        }
        return $input['id'];
    }

    public function task_handl(): void
    {
        $this->handlePostRequest(function ($input) {
            $taskId = $this->validateTaskId($input);
            $this->actionService->update_task_status($taskId);

            $user = $this->getUserFromSession();
            $this->statService->new_stat($taskId, $user->id);

            $this->jsonResponse(['status' => 'success', 'message' => 'perform', 'id' => $taskId]);
        });
    }

    public function help_task(): void
    {
        $this->handlePostRequest(function ($input) {
            $taskId = $this->validateTaskId($input);
            $creator = $this->actionService->find_creator_task($taskId);
            $user = $this->getUserFromSession();

            $notifications = $this->notService->get_not_id($taskId);
            if (empty($notifications)) {
                $this->notService->create_not($taskId, $user->id, $creator['creator_id']);
                $this->statService->status_help($taskId);

                $this->jsonResponse([
                    'status' => 'success',
                    'message' => 'help',
                    'id' => $taskId,
                    'creator_notifications' => $user->id
                ]);
            } else {
                $this->jsonResponse(['status' => 'success', 'message' => 'notification already exists']);
            }
        });
    }

    public function cancel_task(): void
    {
        $this->handlePostRequest(function ($input) {
            $taskId = $this->validateTaskId($input);
            $task = $this->actionService->select_status_task($taskId);

            if (in_array($task['status_id'], [2, 4])) {
                $this->actionService->status_cancel($taskId);

                $user = $this->getUserFromSession();
                $this->statService->status_cancel($taskId, $user->id);

                $this->jsonResponse([
                    'status' => 'success',
                    'message' => 'cancel',
                    'id' => $taskId,
                    'newStatus' => 'Cancelled'
                ]);
            } else {
                $this->jsonResponse(['status' => 'false', 'message' => 'This task cannot be canceled']);
            }
        });
    }

    private function handlePostRequest(callable $callback): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = $this->getJsonInput();
            try {
                $callback($input);
            } catch (\Exception $e) {
                $this->jsonResponse(['success' => false, 'message' => $e->getMessage()]);
            }
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid request method']);
        }
    }

    private function jsonResponse(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}