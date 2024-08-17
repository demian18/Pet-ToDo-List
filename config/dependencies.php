<?php

use Core\Repository\ActionRepository;
use Core\Repository\CommentRepository;
use Core\Repository\NotificationsRepository;
use Core\Repository\StatRepository;
use Core\Repository\TaskRepository;
use Core\Repository\UserRepository;
use Core\Services\Action;
use Core\Services\Comment;
use Core\Services\Notifications;
use Core\Services\Stat;
use Core\Services\Task;
use Core\Services\User;
use Http\controllers\ActionController;
use Http\controllers\AdminController;
use Http\controllers\CommentController;
use Http\controllers\NotificationController;
use Http\controllers\ProfileController;
use Http\controllers\TaskController;
use Http\controllers\UserController;
use Core\App;
use Core\Request;
use Illuminate\Translation\Translator;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Validation\Factory;
use Core\Services\Auth;

return [
    'db' => function () {
        $config = require base_path('config.php');
        $capsule = new \Illuminate\Database\Capsule\Manager;
        $capsule->addConnection($config['database']);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        return $capsule;
    },

    'validationFactory' => function () {
        $loader = new ArrayLoader();
        $translator = new Translator($loader, 'en');
        return new Factory($translator);
    },
    Core\Request::class => fn() => new Core\Request(),

    // Repositories
    UserRepository::class => fn() => new UserRepository(App::resolve('db')),
    TaskRepository::class => fn() => new TaskRepository(App::resolve('db')),
    StatRepository::class => fn() => new StatRepository(App::resolve('db')),
    ActionRepository::class => fn() => new ActionRepository(App::resolve('db')),
    NotificationsRepository::class => fn() => new NotificationsRepository(App::resolve('db')),
    CommentRepository::class => fn() => new CommentRepository(App::resolve('db')),

    // Services
    User::class => fn() => new User(App::resolve(UserRepository::class)),
    Task::class => fn() => new Task(App::resolve(TaskRepository::class)),
    Stat::class => fn() => new Stat(App::resolve(StatRepository::class)),
    Action::class => fn() => new Action(App::resolve(ActionRepository::class)),
    Notifications::class => fn() => new Notifications(App::resolve(NotificationsRepository::class)),
    Comment::class => fn() => new Comment(App::resolve(CommentRepository::class)),
    Auth::class => fn() => new Auth(App::resolve(UserRepository::class)),

    // Controllers
    CommentController::class => function () {
        return new CommentController(
            App::resolve(User::class),
            App::resolve(Notifications::class),
            App::resolve(Comment::class),
            App::resolve(Request::class)
        );
    },
    TaskController::class => function () {
        return new TaskController(
            App::resolve(User::class),
            App::resolve(Task::class),
            App::resolve(Request::class)
        );
    },
    ProfileController::class => function () {
        return new ProfileController(
            App::resolve(User::class),
            App::resolve(Stat::class)
        );
    },
    ActionController::class => function () {
        return new ActionController(
            App::resolve(User::class),
            App::resolve(Action::class),
            App::resolve(Stat::class),
            App::resolve(Notifications::class)
        );
    },
    AdminController::class => function () {
        return new AdminController(
            App::resolve(User::class),
            App::resolve(Task::class),
            App::resolve(Core\Request::class)
        );
    },
    UserController::class => function () {
        return new UserController(
            App::resolve('validationFactory'),
            App::resolve(Auth::class)
        );
    },
    NotificationController::class => function () {
        return new NotificationController(
            App::resolve(User::class),
            App::resolve(Notifications::class),
            App::resolve(Task::class),
            App::resolve(Request::class)
        );
    },
];
