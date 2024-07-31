<?php

namespace Core\Repository;

use Core\Database;
use Models\User;

class UserRepository
{
    protected $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function findByEmail($email)
    {
        $data = $this->db->query('SELECT * FROM users WHERE email = :email', [
            'email' => $email
        ])->find();

        if ($data) {
            return new User($data['id'], $data['name'], $data['email'], $data['password']);
        }

        return null;
    }

    public function create($email, $password)
    {
        return $this->db->query('INSERT INTO users (email, password) VALUES (:email, :password)', [
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
        ]);
    }
}