<?php

namespace Http\Forms;

use Core\Validator;

class ProfileForm
{
    private $data;
    private $errors = [];

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function validate()
    {
        if (!Validator::string($this->data['name'], 3, 15)) {
            $this->errors['name'] = 'Name must be between 3 and 15 characters!';
        }

        if (!Validator::string($this->data['username'], 3, 15)) {
            $this->errors['username'] = 'Username must be between 3 and 15 characters!';
        }

        return empty($this->errors);
    }
    public function errors()
    {
        return $this->errors;
    }

    public function get($key)
    {
        return $this->data[$key] ?? null;
    }
}