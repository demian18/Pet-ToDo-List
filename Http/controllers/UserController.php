<?php

namespace Http\controllers;

use Core\App;
use Core\Repository\UserRepository;
use Core\Services\Auth;
use Core\Session;

class UserController
{
    private $validationFactory;
    private $auth;
    public function __construct()
    {
        $this->validationFactory = App::resolve('validationFactory');
        $this->auth = new Auth(App::resolve(UserRepository::class));
    }

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

    public function register(): void
    {
        $data = [
            'email' => $_POST['email'],
            'password' => $_POST['password'],
        ];

        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];
        $validator = $this->validationFactory->make($data, $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            Session::flash('errors', $errors->toArray());
            redirect('/register');
        }

        if (!$this->auth->register($_POST['email'], $_POST['password'])) {
            Session::flash('errors', ['email' => 'A user with this email already exists.']);
            redirect('/register');
        }

        redirect('/');
    }

    public function login(): void
    {
        $data = [
            'email' => $_POST['email'],
            'password' => $_POST['password'],
        ];

        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];
        $validator = $this->validationFactory->make($data, $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            Session::flash('errors', $errors->toArray());
            redirect('/login');
        }

        if (!$this->auth->attempt($_POST['email'], $_POST['password'])) {
            Session::flash('errors', ['email' => 'No matching account find for that email address and password.']);
            redirect('/login');
        }
        redirect('/');
    }

    public function logout(): void
    {
        $this->auth->logout();
        redirect('/');
    }
}