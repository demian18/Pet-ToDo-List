<?php

use Core\Repository\ProfileRepository;
use Http\Forms\ProfileForm;

$profileRepository = new ProfileRepository();

$id = $_POST['id'];

$user = $profileRepository->editProfile($id);

$form = new ProfileForm($_POST);
if (!$form->validate()) {
    return view("profile/edit.view.php", [
        'errors' => $form->errors(),
        'user' => $user
    ]);
}

$profileRepository->updateProfile($_POST);

header('Location: /profile');
exit();