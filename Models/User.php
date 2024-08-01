<?php

namespace Models;

class User
{
    private $id;
    private $name;
    private $email;
    private $password;
    private $role_id;

    public function __construct($id, $name, $email, $password, $role_id)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role_id = $role_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function checkPassword($password)
    {
        return password_verify($password, $this->password);
    }

    public function getRole_id()
    {
        return $this->role_id;
    }
}
