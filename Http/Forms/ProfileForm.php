<?php

namespace Http\Forms;

use Core\App;

class ProfileForm
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
            'name' => 'required|string|min:3|max:15',
            'username' => 'required|min:3|max:15',
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