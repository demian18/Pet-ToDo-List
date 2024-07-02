<?php

use Core\Registration;
use Http\Forms\LoginForm;

$form = LoginForm::validate($attributes = [
    'email' => $_POST['email'],
    'password' => $_POST['password']
]);

$registered = (new Registration)->attempt(
    $attributes['email'], $attributes['password']
);

if (!$registered) {
    $form->error(
        'email', 'A user with this email already exists.'
    )->throw();
}

redirect('/');