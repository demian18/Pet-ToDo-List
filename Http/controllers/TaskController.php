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
    public function index()
    {
        $errors = [];
        if (isset($_GET['errors'])) {
            $errors = json_decode($_GET['errors'], true);
        }

        $userService = new User(App::resolve(UserRepository::class));

        $session_user = Session::get('user');

        if ($session_user == null) {
            view("index.view.php");
            exit();
        } else {
            $email = $session_user['email'];

            $user = $userService->findUser($email);

            $user_id = $user->getId();
            $role = $user->getRole_id();

            $taskService = new Task(App::resolve(TaskRepository::class));

            if ($role == 2) { // Admin

                $users = $userService->getWorkers();

                $tasks = $taskService->getTasksAdmin($user_id);

                view("index.view.php", [
                    'errors' => $errors,
                    'tasks' => $tasks,
                    'role' => $role,
                    'users' => $users,
                    'user_id' => $user_id,
                ]);
            } elseif ($role == 1) { // Worker
                $tasks = $taskService->getTasksWorker($user_id);

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
        $form = new TaskForm($_POST);

        if (!$form->validate()) {
            $errorParams = http_build_query(['errors' => json_encode($form->errors())]);
            header("Location: /?$errorParams");
            exit();
        }
        $session_user = Session::get('user');
        $email = $session_user['email'];

        $userService = new User(App::resolve(UserRepository::class));
        $user = $userService->findByEmail($email);

        $taskService = new Task(App::resolve(TaskRepository::class));
        $taskService->create([
            'title' => $form->get('title'),
            'assignee_id' => $form->get('assignee'),
            'creator_id' => $user->getId(),
        ]);

        header('Location: /');
        exit();
    }

    public function edit(): void
    {
        $id = $_GET['id'];

        $taskService = new Task(App::resolve(TaskRepository::class));
        $task = $taskService->edit((int)$id);

        view("edit.view.php", [
            'task' => $task
        ]);
    }

    public function update()
    {
        $taskService = new Task(App::resolve(TaskRepository::class));

        $id = $_POST['id'];
        $task = $taskService->edit($id);

        $form = new TaskForm($_POST);
        if (!$form->validate()) {
            return view("edit.view.php", [
                'errors' => $form->errors(),
                'task' => $task
            ]);
        }
        $taskService->update($_POST);

        header('Location: /');
        exit();
    }

    public function delete()
    {
        $id = $_POST['id'];

        $taskService = new Task(App::resolve(TaskRepository::class));
        $taskService->delete($id);

        header('Location: /');
        exit();
    }
}