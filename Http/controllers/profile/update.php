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

if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['photo'];
    $allowedExtensions = ['jpg', 'jpeg', 'png'];
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    $fileName = uniqid() . '.' . $fileExtension;
    $uploadDir = __DIR__ . '/../../../public/uploads/profile_photos/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $filePath = $uploadDir . $fileName;

    $profileRepository->updateProfilePhoto($id, $fileName);
}
$profileRepository->updateProfile($_POST);
header('Location: /profile');
exit();