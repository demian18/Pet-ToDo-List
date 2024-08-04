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

    public function findUser($email): ?\Models\User
    {
        return $this->userRepo->findUser($email);
    }

    public function getWorkers()
    {
        return $this->userRepo->getWorkers();
    }

    public function editUser($id)
    {
        return $this->userRepo->edit($id);
    }

    public function update($data): void
    {
        $this->userRepo->updateProfile($data);
    }

    public function updatePhoto($id, $fileName): void
    {
        $this->userRepo->updateProfilePhoto($id, $fileName);
    }
}