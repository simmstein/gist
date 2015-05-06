<?php

use GitWrapper\GitWrapper;
use Gist\Service\GistService;

$app['gist_path'] = $app['root_path'].'/data/git';

$app['git'] = function ($app) {
    $wrapper = new GitWrapper('/usr/bin/git');
    return $wrapper->init($app['gist_path']);
};

$app['gist'] = function ($app) {
    return new GistService($app['gist_path'], $app['git']);
};
