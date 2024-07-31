<?php

namespace Core;

use Core\Repository\UserRepository;

class Auth
{
    protected $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function register($email, $password)
    {
        if ($this->userRepo->findByEmail($email)) {
            return false;
        }

        $this->userRepo->create($email, $password);
        $this->login(['email' => $email]);

        return true;
    }

    public function attempt($email, $password)
    {
        $user = $this->userRepo->findByEmail($email);

        if ($user && password_verify($password, $user->getPassword())) {
            $this->login([
                'email' => $email
            ]);
            return true;
        }
        return false;
    }

    public function login($user)
    {
        $_SESSION['user'] = [
            'email' => $user['email']
        ];

        session_regenerate_id(true);
    }

    public function logout()
    {
        Session::destroy();
    }
}