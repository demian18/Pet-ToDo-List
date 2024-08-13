<?php

namespace Http\controllers;

use Core\App;
use Core\Repository\StatRepository;
use Core\Repository\TaskRepository;
use Core\Repository\UserRepository;
use Core\Services\Stat;
use Core\Services\Task;
use Core\Services\User;
use Core\Session;

class AdminController
{
    public function index()
    {
        $userService = new User(App::resolve(UserRepository::class));

        $session_user = Session::get('user');
        $email = $session_user['email'];

        $user = $userService->findByEmail($email);
        $user_id = $user->id;

        $taskService = new Task(App::resolve(TaskRepository::class));
        $tasks = $taskService->get_task_admin($user_id);

        view('admin/index.view.php', [
            "tasks" => $tasks
        ]);
    }

    public function cancel()
    {
        $taskId = $_POST['id'];

        $taskService = new Task(App::resolve(TaskRepository::class));
        $task = $taskService->get_task_assignee($taskId);

        $statService = new Stat(App::resolve(StatRepository::class));

        if ($task['status_id'] != 3) {

            $taskService->update_status_canceled($taskId);

            $updatedTask = $taskService->get_status_task($taskId);
            if ($updatedTask['status_id'] == 1) {
                $statService->update_status_cancel($taskId);
            }
        }
        header('Location:/tasks');
        exit();
    }
}