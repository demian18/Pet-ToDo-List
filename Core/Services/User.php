<?php

namespace Core\Services;

use Core\Repository\UserRepository;

class User
{
    protected UserRepository $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function findByEmail($email): ?\Models\User
    {
        return $this->userRepo->findByEmail($email);
    }
}