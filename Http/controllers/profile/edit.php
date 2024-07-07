<?php

use Core\Repository\ProfileRepository;
use Core\Session;

$session_user = Session::get('user');
$email = $session_user['email'];

$profileRepository = new ProfileRepository();

$user = $profileRepository->findUser($email);

view('profile/edit.view.php', [
    'user' => $user
]);