<?php

namespace Core;

use Core\Repository\UserRepository;

class Registration
{
    protected $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function attempt($email, $password)
    {
        $user = $this->userRepo->findByEmail($email);

        if ($user) {
            return false;
        } else {
            $this->userRepo->create($email, $password);

            $this->login([
                'email' => $email
            ]);
            return true;
        }
    }

    public function login($user)
    {
        $_SESSION['user'] = [
            'email' => $user['email']
        ];

        session_regenerate_id(true);
    }
}
