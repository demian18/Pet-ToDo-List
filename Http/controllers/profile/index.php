<?php

use Core\Repository\ProfileRepository;
use Core\Repository\StatRepository;
use Core\Session;
use Carbon\Carbon;

$session_user = Session::get('user');
$email = $session_user['email'];

$profileRepository = new ProfileRepository();
$statRepository = new StatRepository();

$user = $profileRepository->findUser($email);
$user_id = $profileRepository->findUserById($email);

$finTasks = $statRepository->finishedTask($user_id['id']);

$cpmTasks = 0;
$cndTasks = 0;
foreach ($finTasks as $task) {
    if ($task['status_id'] === 1) {
        $cpmTasks++;
    } elseif ($task['status_id'] === 3) {
        $cndTasks++;
    }
}

$carbon = Carbon::today();
$userPeriod = Carbon::parse($user['period']);
$time = $userPeriod->diffForHumans();

$userPhoto = $user['picture'];

$defaultPhoto = "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80";

$photoPath = $userPhoto ? '/uploads/profile_photos/' . $userPhoto : $defaultPhoto;

view('profile/index.view.php', [
    'user' => $user,
    'photoPath' => $photoPath,
    'time' => $time,
    'cpmTasks' => $cpmTasks,
    'cndTasks' => $cndTasks,
]);