<?php

use Core\App;
use Core\Container;
use Core\Database;
use Core\Repository\ActionRepository;
use Core\Repository\CommentRepository;
use Core\Repository\NotificationsRepository;
use Core\Repository\StatRepository;
use Core\Repository\TaskRepository;
use Core\Repository\UserRepository;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Translation\Translator;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Validation\Factory;

$container = new Container();

$container->bind('Core\Database', function (){
    $config = require base_path('config.php');

    $capsule = new Capsule;
    $capsule->addConnection($config['database']);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
});

App::setContainer($container);

$loader = new ArrayLoader();
$translator = new Translator($loader, 'en');
$validationFactory = new Factory($translator);

App::bind('validationFactory', function() use ($validationFactory) {
    return $validationFactory;
});

App::bind(UserRepository::class, function() {
    return new UserRepository(App::resolve(Database::class));
});

App::bind(TaskRepository::class, function() {
    return new TaskRepository(App::resolve(Database::class));
});

App::bind(StatRepository::class, function() {
    return new StatRepository(App::resolve(Database::class));
});

App::bind(ActionRepository::class, function() {
    return new ActionRepository(App::resolve(Database::class));
});

App::bind(NotificationsRepository::class, function() {
    return new NotificationsRepository(App::resolve(Database::class));
});

App::bind(CommentRepository::class, function() {
    return new CommentRepository(App::resolve(Database::class));
});
/*$container->bind('logger', function() {
    $config = require base_path('config/log.php');
    $log = new Logger('app');

    foreach ($config['channels'] as $channel) {
        $log->pushHandler(new StreamHandler($channel['path'], $channel['level']));
    }

    return $log;
});*/