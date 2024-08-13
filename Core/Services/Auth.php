<?php

namespace Core\Services;

use Core\Repository\UserRepository;
use Core\Session;

class Auth
{
    protected UserRepository $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function register($email, $password): bool
    {
        if ($this->userRepo->findByEmail($email)) {
            return false;
        }

        $this->userRepo->create($email, $password);
        $this->login(['email' => $email]);

        return true;
    }

    public function attempt($email, $password): bool
    {
        $user = $this->userRepo->findByEmail($email);
        if ($user && password_verify($password, $user->password)) {
            $this->login([
                'email' => $email
            ]);
            return true;
        }
        return false;
    }

    public function login($user): void
    {
        $_SESSION['user'] = [
            'email' => $user['email']
        ];

        session_regenerate_id(true);
    }

    public function logout(): void
    {
        Session::destroy();
    }
}