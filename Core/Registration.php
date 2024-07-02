<?php

namespace Core;

class Registration
{
    public function attempt($email, $password)
    {
        $user = App::resolve(Database::class)
            ->query('select * from users where email = :email', [
                'email' => $email
            ])->find();

        if ($user) {
            return false;
        } else {
            App::resolve(Database::class)->query('insert into users(email, password) values(:email, :password)', [
                'email' => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT),
            ]);

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