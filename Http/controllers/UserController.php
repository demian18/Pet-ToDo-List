<?php

namespace Http\controllers;

use Core\App;
use Core\Repository\UserRepository;
use Core\Request;
use Core\Services\Auth;
use Core\Session;
use Illuminate\Validation\Factory as ValidationFactory;

class UserController
{
    private ValidationFactory $validationFactory;
    private Auth $auth;
    private Request $request;
    public function __construct(ValidationFactory $validationFactory, Auth $auth, Request $request)
    {
        $this->validationFactory = $validationFactory;
        $this->auth = $auth;
        $this->request = $request;
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
        $email = $this->request->post('email');
        $password = $this->request->post('password');
        $data = [
            'email' => $email,
            'password' => $password,
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

        if (!$this->auth->register($email, $password)) {
            Session::flash('errors', ['email' => 'A user with this email already exists.']);
            redirect('/register');
        }

        redirect('/');
    }

    public function login(): void
    {
        $email = $this->request->post('email');
        $password = $this->request->post('password');
        $data = [
            'email' => $email,
            'password' => $password,
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

        if (!$this->auth->attempt($email, $password)) {
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