<?php

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;

$app['config.locator.path'] = $app['root_path'].'/app/config/';

$app['config.locator'] = function ($app) {
    return new FileLocator($app['config.locator.path']);
};

$app['settings'] = $app->share(function ($app) {
    return Yaml::parse($app['config.locator']->locate('config.yml'));
});
