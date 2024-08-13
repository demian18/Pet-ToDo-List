<?php

namespace Http\controllers;

use Core\App;
use Core\Repository\TaskRepository;
use Core\Repository\UserRepository;
use Core\Services\Task;
use Core\Services\User;

class FilterController
{
    public function get()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $status = $input['status'];
            $email = $_SESSION['user']['email'];

            $userService = new User(App::resolve(UserRepository::class));
            $user = $userService->findByEmail($email);
            $user_id = $user->id;

            $taskService = new Task(App::resolve(TaskRepository::class));

            if ($status === 'all') {
                $tasks = $taskService->get_all($user_id);
            } elseif ($status === 'completed') {
                $tasks = $taskService->get_completed($status, $user_id);
            } elseif ($status === 'help') {
                $tasks = $taskService->get_help($status, $user_id);
            } elseif ($status === 'canceled') {
                $tasks = $taskService->get_canceled($status, $user_id);
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