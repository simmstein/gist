<?php

use Gist\Application;

$app = Application::getInstance();

$app['root_path'] = __DIR__ . '/../..';

chdir($app['root_path']);
