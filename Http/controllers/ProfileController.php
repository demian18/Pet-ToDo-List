<?php

namespace Http\controllers;

use Core\App;
use Core\Repository\StatRepository;
use Core\Repository\UserRepository;
use Core\Services\Stat;
use Core\Services\User;
use Core\Session;
use Carbon\Carbon;
use Http\Forms\ProfileForm;

class ProfileController
{
    private User $userService;
    private Stat $statService;

    public function __construct()
    {
        $this->userService = new User(App::resolve(UserRepository::class));
        $this->statService = new Stat(App::resolve(StatRepository::class));
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
        $user_id = $user->getId();

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
        $userPeriod = Carbon::parse($user->getPeriod());
        $time = $userPeriod->diffForHumans();

        $userPhoto = $user->getPicture();

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
        $id = $_POST['id'];

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

            $fileName = uniqid() . '.' . $fileExtension;
            $uploadDir = __DIR__ . '/../../../public/uploads/profile_photos/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $filePath = $uploadDir . $fileName;

            $this->userService->updatePhoto($id, $fileName);
        }
        $this->userService->update($_POST);
        header('Location: /profile');
        exit();
    }
}