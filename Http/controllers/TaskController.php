<?php

namespace Http\controllers;

use Core\App;
use Core\Repository\TaskRepository;
use Core\Repository\UserRepository;
use Core\Request;
use Core\Services\Task;
use Core\Services\User;
use Core\Session;
use Http\Forms\TaskForm;

class TaskController
{
    private User $userService;
    private Task $taskService;
    private Request $request;

    public function __construct(User $userService, Task $taskService, Request $request)
    {
        $this->userService = $userService;
        $this->taskService = $taskService;
        $this->request = $request;
    }

    private function getUserFromSession(): ?\Models\User
    {
        $session_user = Session::get('user');
        $email = $session_user['email'];
        return $this->userService->findByEmail($email);
    }

    private function handleErrors(): array
    {
        $errors = [];
        if ($this->request->query('errors') !== null) {
            $errors = json_decode($this->request->query('errors'), true);
        }
        return $errors;
    }

    private function redirectWithErrors(array $errors): void
    {
        $errorParams = http_build_query(['errors' => json_encode($errors)]);
        header("Location: /?$errorParams");
        exit();
    }

    private function isAdmin(int $role): bool
    {
        return $role === 2;
    }

    private function isWorker(int $role): bool
    {
        return $role === 1;
    }

    public function index()
    {
        $errors = $this->handleErrors();
        $user = $this->getUserFromSession();

        if (!$user) {
            view("index.view.php");
            exit();
        }

        $user_id = $user->id;
        $role = $user->role_id;

        if ($this->isAdmin($role)) {
            $users = $this->userService->getWorkers();
            $tasks = $this->taskService->getTasksAdmin($user_id);
        } elseif ($this->isWorker($role)) {
            $tasks = $this->taskService->getTasksWorker($user_id);
        } else {
            $tasks = [];
        }

        view("index.view.php", [
            'errors' => $errors,
            'tasks' => $tasks,
            'role' => $role,
            'users' => $this->isAdmin($role) ? $users : null,
            'user_id' => $user_id,
        ]);
    }

    public function create()
    {
        $user = $this->getUserFromSession();
        $postData = $this->request->post();
        $form = new TaskForm($postData, $user->id);

        if (!$form->validate()) {
            $this->redirectWithErrors($form->errors());
        }

        $this->taskService->create($form->getData());
        header('Location: /');
        exit();
    }

    public function edit(): void
    {
        $id = $this->request->query('id') ?? null;

        if (!$id) {
            header('Location: /');
            exit();
        }

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
        $id = $this->request->post('id') ?? null;

        if (!$id) {
            header('Location: /');
            exit();
        }

        $task = $this->taskService->edit($id);
        $postData = $this->request->post();
        $form = new TaskForm($postData, $user->id);

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
        $id = $this->request->post('id') ?? null;

        if ($id) {
            $this->taskService->delete($id);
        }

        header('Location: /');
        exit();
    }
}