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

    public function findByEmail($email): ?User
    {
        $data = $this->db->query('SELECT * FROM users WHERE email = :email', [
            'email' => $email
        ])->find();

        if ($data) {
            return new User($data['id'], $data['name'], $data['email'], $data['password'], $data['role_id']);
        }

        return null;
    }

    public function findUser($email): ?User
    {
        $data = $this->db->query('SELECT id, role_id FROM users WHERE email = :email', [
            'email' => $email
        ])->findOrFail();

        if ($data) {
            return new User($data['id'], null, null, null, $data['role_id']);
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

    public function getWorkers()
    {
        return $this->db->query('SELECT id, email FROM users WHERE role_id != 2')->get();
    }
}