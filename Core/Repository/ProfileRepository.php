<?php

namespace Core\Repository;

use Core\App;
use Core\Database;

class ProfileRepository
{
    public function findUser($email)
    {
        $user = App::resolve(Database::class)->query('SELECT * FROM users WHERE email = :email', [
            'email' => $email
        ])->findOrFail();
        return $user;
    }

    public function editProfile($id)
    {
        $profile = App::resolve(Database::class)->query('select * from users where id = :id', [
            'id' => $id
        ])->findOrFail();

        return $profile;
    }

    public function updateProfile($data)
    {
        App::resolve(Database::class)->query('update users set email = :email, name = :name, username = :username where id = :id', [
            'id' => $data['id'],
            'email' => $data['email'],
            'name' => $data['name'],
            'username' => $data['username'],
        ]);
    }
}