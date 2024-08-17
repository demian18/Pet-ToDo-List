<?php

namespace Http\controllers;

use Core\Services\Task;
use Core\Services\User;

class FilterController
{
    private User $userService;
    private Task $taskService;

    public function __construct(User $userService, Task $taskService)
    {
        $this->userService = $userService;
        $this->taskService = $taskService;
    }

    public function get()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $status = $input['status'];
            $email = $_SESSION['user']['email'];

            $user = $this->userService->findByEmail($email);
            $user_id = $user->id;

            if ($status === 'all') {
                $tasks = $this->taskService->get_all($user_id);
            } elseif ($status === 'completed') {
                $tasks = $this->taskService->get_completed($status, $user_id);
            } elseif ($status === 'help') {
                $tasks = $this->taskService->get_help($status, $user_id);
            } elseif ($status === 'canceled') {
                $tasks = $this->taskService->get_canceled($status, $user_id);
            }

            ob_start();
            foreach ($tasks as $task) {
                view("task_template.view.php", ['task' => $task]);
            }
            $tasksHtml = ob_get_clean();

            echo json_encode(['tasksHtml' => $tasksHtml]);
        }
    }
}