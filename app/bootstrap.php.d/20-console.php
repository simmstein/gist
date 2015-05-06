<?php

use Knp\Provider\ConsoleServiceProvider;

$app->register(new ConsoleServiceProvider(), array(
    'console.name' => 'GIST Console',
    'console.version' => 'dev-master',
    'console.project_directory' => $app['root_path'],
));

$app['console'] = $app->share($app->extend('console', function ($console) {
    return $console;
}));
