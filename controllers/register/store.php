<?php

use Core\App;
use Core\Database;
use Core\Validator;

$email = $_POST['email'];
$password = $_POST['password'];

$errors = [];

if (!Validator::string($email)) {
    $errors['email'] = 'Please provide a valid email address.';
}

if (!Validator::string($password, 5, 24)) {
    $errors['password'] = 'Please provide a password of at least 5 characters.';
}

if (!empty($errors)) {
    return view('register/create.view.php', [
        'errors' => $errors
    ]);
}

$db = App::resolve(Database::class);

$user = $db->query('select * from users where email = :email', [
    'email' => $email
])->find();

if ($user) {
    header('location: /');
    exit();
} else {
    $db->query('insert into users(email, password) values(:email, :password)', [
        'email' => $email,
        'password' => $password,
    ]);

    $_SESSION['user'] = [
        'email' => $email,
    ];

    header('location: /');
    exit();
}