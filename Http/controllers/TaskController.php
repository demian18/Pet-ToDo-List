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
}