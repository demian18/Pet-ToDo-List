<?php

namespace Http\controllers;

use Core\App;
use Core\Auth;
use Core\Repository\UserRepository;
use Core\Session;
use Http\Forms\LoginForm;

class UserController
{
    public function create_register(): void
    {
        view('/register/create.view.php', [
            'errors' => Session::get('errors')
        ]);
    }

    public function create_session(): void
    {
        view('session/create.view.php', [
            'errors' => Session::get('errors')
        ]);
    }
    public function register()
    {
        $form = LoginForm::validate([
            'email' => $_POST['email'],
            'password' => $_POST['password']
        ]);

        $auth = new Auth(App::resolve(UserRepository::class));

        if (!$auth->register($_POST['email'], $_POST['password'])) {
            $form->error('email', 'A user with this email already exists.'
            )->throw();
        }

        redirect('/');
    }

    public function login()
    {
        $form = LoginForm::validate([
            'email' => $_POST['email'],
            'password' => $_POST['password'],
        ]);

        $auth = new Auth(App::resolve(UserRepository::class));

        if (!$auth->attempt($_POST['email'], $_POST['password'])) {
            $form->error('email', 'No matching account find for that email address and password.'
            )->throw();
        }

        redirect('/');
    }

    public function logout()
    {
        $auth = new Auth(App::resolve(UserRepository::class));
        $auth->logout();
        redirect('/');
    }
}