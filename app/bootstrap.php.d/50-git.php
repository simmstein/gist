<?php

use GitWrapper\GitWrapper;
use Gist\Service\Gist;

$app['gist_path'] = $app['root_path'].'/data/git';

$app['git_wrapper'] = $app->share(function ($app) {
    return new GitWrapper('/usr/bin/git');
});

$app['git_working_copy'] = $app->share(function ($app) {
    return $app['git_wrapper']->init($app['gist_path']);
});

$app['gist'] = $app->share(function ($app) {
    return new Gist($app['gist_path'], $app['git_wrapper'], $app['git_working_copy'], $app['geshi']);
});
