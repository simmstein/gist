<?php

use Gist\Application;

$app = Application::getInstance();

$app['root_path'] = __DIR__ . '/../..';

if (php_sapi_name() !== 'cli') {
    chdir($app['root_path']);
} else {
    set_include_path(get_include_path().PATH_SEPARATOR.$app['root_path']);
}
