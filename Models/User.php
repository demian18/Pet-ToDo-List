<?php

namespace Models;

class User
{
    private $id;
    private $name;
    private $email;
    private $password;
    private $username;
    private $role_id;
    private $picture;
    private $period;

    public function __construct($id, $name, $email, $password, $username, $role_id, $picture, $period)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->username = $username;
        $this->role_id = $role_id;
        $this->picture = $picture;
        $this->period = $period;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password): void
    {
        $this->password = $password;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username): void
    {
        $this->username = $username;
    }

    public function checkPassword($password): bool
    {
        return password_verify($password, $this->password);
    }

    public function getRole_id()
    {
        return $this->role_id;
    }

    public function getPeriod()
    {
        return $this->period;
    }

    public function getPicture()
    {
        return $this->picture;
    }
}
