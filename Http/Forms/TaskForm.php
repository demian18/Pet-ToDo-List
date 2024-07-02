<?php

namespace Http\Forms;

use Core\Validator;

class TaskForm
{
    private $data;
    private $errors = [];

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function validate()
    {
        if (!Validator::string($this->data['title'], 5, 25)) {
            $this->errors['title'] = 'Title must be between 5 and 25 characters!';
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