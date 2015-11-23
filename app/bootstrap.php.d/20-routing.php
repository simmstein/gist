<?php

use Symfony\Component\Routing\Loader\YamlFileLoader;
use Gist\ControllerResolver;

$app['routing.file'] = 'routing.yml';

$app['routing.loader'] = $app->share(function ($app) {
    return new YamlFileLoader($app['config.locator']);
});

$app['routes'] = $app->extend('routes', function ($routes, $app) {
    $routes->addCollection($app['routing.loader']->load($app['routing.file']));

    return $routes;
});

$app['resolver'] = $app->share(function () use ($app) {
    if (isset($app['logger'])){
        $logger = $app['logger'];
    } else{
        $logger = null;
    }

    return new ControllerResolver($app, isset($app['logger']) ? $app['logger'] : null);
});
