<?php

use Core\Repository\ProfileRepository;
use Core\Session;
use Carbon\Carbon;

$session_user = Session::get('user');
$email = $session_user['email'];

$profileRepository = new ProfileRepository();

$user = $profileRepository->findUser($email);
$carbon = Carbon::today();
$userPeriod = Carbon::parse($user['period']);
$time = $userPeriod->diffForHumans();

view('profile/index.view.php', [
    'user' => $user,
    'time' => $time,
]);