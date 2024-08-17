<?php

use Core\App;
use Core\Container;
use Core\Repository\ActionRepository;
use Core\Repository\CommentRepository;
use Core\Repository\NotificationsRepository;
use Core\Repository\StatRepository;
use Core\Repository\TaskRepository;
use Core\Repository\UserRepository;
use Illuminate\Translation\Translator;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Validation\Factory;

$container = new Container();

$container->bind('db', function () {
    $config = require base_path('config.php');
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($config['database']);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
    return $capsule;
});

App::setContainer($container);

$loader = new ArrayLoader();
$translator = new Translator($loader, 'en');
$validationFactory = new Factory($translator);

App::bind('validationFactory', function () use ($validationFactory) {
    return $validationFactory;
});

App::bind(UserRepository::class, function () {
    return new UserRepository(App::resolve('db'));
});

App::bind(TaskRepository::class, function () {
    return new TaskRepository(App::resolve('db'));
});

App::bind(StatRepository::class, function () {
    return new StatRepository(App::resolve('db'));
});

App::bind(ActionRepository::class, function () {
    return new ActionRepository(App::resolve('db'));
});

App::bind(NotificationsRepository::class, function () {
    return new NotificationsRepository(App::resolve('db'));
});

App::bind(CommentRepository::class, function () {
    return new CommentRepository(App::resolve('db'));
});
/*$container->bind('logger', function() {
    $config = require base_path('config/log.php');
    $log = new Logger('app');

    foreach ($config['channels'] as $channel) {
        $log->pushHandler(new StreamHandler($channel['path'], $channel['level']));
    }

    return $log;
});*/