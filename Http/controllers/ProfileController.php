<?php

namespace Http\controllers;

use Core\App;
use Core\Repository\StatRepository;
use Core\Repository\UserRepository;
use Core\Request;
use Core\Services\Stat;
use Core\Services\User;
use Core\Session;
use Carbon\Carbon;
use Http\Forms\ProfileForm;

class ProfileController
{
    private User $userService;
    private Stat $statService;
    private Request $request;

    public function __construct(User $userService, Stat $statService, Request $request)
    {
        $this->userService = $userService;
        $this->statService = $statService;
        $this->request = $request;
    }

    private function getUserFromSession(): ?\Models\User
    {
        $session_user = Session::get('user');
        $email = $session_user['email'];
        return $this->userService->findByEmail($email);
    }

    public function index(): void
    {
        $user = $this->getUserFromSession();
        $user_id = $user->id;

        $finTasks = $this->statService->finishedTask($user_id);

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
        $userPeriod = Carbon::parse($user->period);
        $time = $userPeriod->diffForHumans();

        $userPhoto = $user->picture;

        $defaultPhoto = "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80";

        $photoPath = $userPhoto ? '/uploads/profile_photos/' . $userPhoto : $defaultPhoto;

        view('profile/index.view.php', [
            'user' => $user,
            'photoPath' => $photoPath,
            'time' => $time,
            'cpmTasks' => $cpmTasks,
            'cndTasks' => $cndTasks,
        ]);
    }

    public function edit(): void
    {
        $user = $this->getUserFromSession();

        view('profile/edit.view.php', [
            'user' => $user
        ]);
    }

    public function update()
    {
        $id = $this->request->post('id');

        $user = $this->userService->editUser($id);

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

            if (in_array($fileExtension, $allowedExtensions)) {
                $fileName = uniqid() . '.' . $fileExtension;
                $uploadDir = __DIR__ . '/../../public/uploads/profile_photos/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $filePath = $uploadDir . $fileName;
                if (move_uploaded_file($file['tmp_name'], $filePath)) {
                    $this->userService->updatePhoto($id, $fileName);
                } else {
                    return view("profile/edit.view.php", [
                        'errors' => ['photo' => 'Error uploading the file. Please try again.'],
                        'user' => $user
                    ]);
                }
            } else {
                return view("profile/edit.view.php", [
                    'errors' => ['photo' => 'Unsupported file format. Allowed formats are jpg, jpeg, png.'],
                    'user' => $user
                ]);
            }
        }

        $this->userService->update($_POST);
        header('Location: /profile');
        exit();
    }
}