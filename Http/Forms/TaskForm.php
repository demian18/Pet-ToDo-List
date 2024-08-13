<?php

namespace Http\Forms;

use Core\App;

class TaskForm
{
    private $data;
    private $errors = [];
    private $validator;

    public function __construct($data)
    {
        $this->data = $data;
        $this->validator = App::resolve('validationFactory');
    }

    public function validate()
    {
        $rules = [
            'title' => 'required|string|min:5|max:25',
            'assignee' => 'required',
        ];

        $validator = $this->validator->make($this->data, $rules);

        if ($validator->fails()) {
            $this->errors = $validator->errors()->toArray();
            return false;
        }

        return true;
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