<?php

namespace Http\controllers;

use Core\App;
use Core\Repository\TaskRepository;
use Core\Repository\UserRepository;
use Core\Services\Task;
use Core\Services\User;
use Core\Session;
use Http\Forms\TaskForm;

class TaskController
{
    private User $userService;
    private Task $taskService;

    public function __construct(User $userService, Task $taskService)
    {
        $this->userService = $userService;
        $this->taskService = $taskService;
    }

    private function getUserFromSession(): ?\Models\User
    {
        $session_user = Session::get('user');
        $email = $session_user['email'];
        return $this->userService->findByEmail($email);
    }

    public function index()
    {
        $errors = [];
        if (isset($_GET['errors'])) {
            $errors = json_decode($_GET['errors'], true);
        }
        $user = $this->getUserFromSession();
        if ($user == null) {
            view("index.view.php");
            exit();
        } else {
            $user_id = $user->id;
            $role = $user->role_id;
            if ($role == 2) {
                $users = $this->userService->getWorkers();
                $tasks = $this->taskService->getTasksAdmin($user_id);

                view("index.view.php", [
                    'errors' => $errors,
                    'tasks' => $tasks,
                    'role' => $role,
                    'users' => $users,
                    'user_id' => $user_id,
                ]);
            } elseif ($role == 1) { // Worker
                $tasks = $this->taskService->getTasksWorker($user_id);

                view("index.view.php", [
                    'errors' => $errors,
                    'tasks' => $tasks,
                    'role' => $role,
                    'user_id' => $user_id,
                ]);
            }
        }
    }

    public function create()
    {
        $user = $this->getUserFromSession();

        $form = new TaskForm($_POST, $user->id);

        if (!$form->validate()) {
            $errorParams = http_build_query(['errors' => json_encode($form->errors())]);
            header("Location: /?$errorParams");
            exit();
        }

        $this->taskService->create($form->getData());

        header('Location: /');
        exit();
    }

    public function edit(): void
    {
        $id = $_GET['id'];

        $task = $this->taskService->edit((int)$id);
        $users = $this->userService->getWorkers();

        view("edit.view.php", [
            'task' => $task,
            'users' => $users
        ]);
    }

    public function update()
    {
        $user = $this->getUserFromSession();

        $id = $_POST['id'];
        $task = $this->taskService->edit($id);

        $form = new TaskForm($_POST, $user->id);

        if (!$form->validate()) {
            return view("edit.view.php", [
                'errors' => $form->errors(),
                'task' => $task
            ]);
        }
        $this->taskService->update($form->getData());

        header('Location: /');
        exit();
    }

    public function delete()
    {
        $id = $_POST['id'];

        $this->taskService->delete($id);

        header('Location: /');
        exit();
    }
}