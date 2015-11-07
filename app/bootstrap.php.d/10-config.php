<?php

use Symfony\Component\Config\FileLocator;

$app['config.locator.path'] = $app['root_path'].'/app/config/';

$app['config.locator'] = function ($app) {
    return new FileLocator($app['config.locator.path']);
};

$app['env'] = 'prod';
