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
use Core\Request;

class AdminController
{
    private User $userService;
    private Task $taskService;
    private Request $request;

    public function __construct()
    {
        $this->userService = new User(App::resolve(UserRepository::class));
        $this->taskService = new Task(App::resolve(TaskRepository::class));
        $this->request = new Request();
    }

    public function index(): void
    {
        $session_user = Session::get('user');
        $email = $session_user['email'];

        $user = $this->userService->findByEmail($email);
        $user_id = $user->id;

        $tasks = $this->taskService->get_task_admin($user_id);

        view('admin/index.view.php', [
            "tasks" => $tasks
        ]);
    }

    public function cancel(): void
    {
        $taskId = $this->request->post('id');

        $task = $this->taskService->get_task_assignee($taskId);

        $statService = new Stat(App::resolve(StatRepository::class));

        if ($task['status_id'] != 3) {

            $this->taskService->update_status_canceled($taskId);

            $updatedTask = $this->taskService->get_status_task($taskId);
            if ($updatedTask['status_id'] == 1) {
                $statService->update_status_cancel($taskId);
            }
        }
        header('Location:/tasks');
        exit();
    }
}