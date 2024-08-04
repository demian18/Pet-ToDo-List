<?php

namespace Core\Repository;

use Core\App;
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
            return new User($data['id'], $data['name'], $data['email'], $data['password'], $data['username'], $data['role_id'],
                $data['picture'], $data['period']);
        }

        return null;
    }

    public function findUser($email): ?User
    {
        $data = $this->db->query('SELECT id, role_id FROM users WHERE email = :email', [
            'email' => $email
        ])->findOrFail();

        if ($data) {
            return new User($data['id'], null, null, null, null, $data['role_id'],
                null, null);
        }

        return null;
    }

    public function create($email, $password): Database
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

    public function edit($id)
    {
        return $this->db->query('SELECT * FROM users WHERE id = :id', [
            'id' => $id
        ])->findOrFail();
    }
    public function updateProfile($data): void
    {
        App::resolve(Database::class)->query('update users set email = :email, name = :name, username = :username where id = :id', [
            'id' => $data['id'],
            'email' => $data['email'],
            'name' => $data['name'],
            'username' => $data['username'],
        ]);
    }
    public function updateProfilePhoto($id, $fileName): void
    {
        App::resolve(Database::class)->query('UPDATE users SET picture = :picture WHERE id = :id', [
            'picture' => $fileName,
            'id' => $id,
        ]);
    }
}