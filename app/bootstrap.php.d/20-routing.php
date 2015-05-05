<?php

use Symfony\Component\Routing\Loader\YamlFileLoader;

$app['routing.file'] = 'routing.yml';

$app['routing.loader'] = function ($app) {
    return new YamlFileLoader($app['config.locator']);
};

$app['routes'] = $app->extend('routes', function ($routes, $app) {
    $routes->addCollection($app['routing.loader']->load($app['routing.file']));

    return $routes;
});
