<?php

namespace Core\Repository;

use Models\User;

class UserRepository
{
    public function findByEmail($email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function findUser($email): ?User
    {
        $user = User::select('id', 'role_id')
            ->where('email', $email)
            ->first();

        return $user;
    }

    public function create($email, $password): User
    {
        $user = User::create([
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT)
        ]);
        return $user;
    }

    public function getWorkers()
    {
        return User::where('role_id', '!=', 2)->get(['id', 'email']);
    }

    public function edit($id)
    {
        return User::where('id', $id)->first();
    }

    public function updateProfile($data): void
    {
        User::where('id', $data['id'])->update([
            'email' => $data['email'],
            'name' => $data['name'],
            'username' => $data['username']
        ]);
    }

    public function updateProfilePhoto($id, $fileName): void
    {
        User::where('id', $id)->update(['picture' => $fileName]);
    }
}