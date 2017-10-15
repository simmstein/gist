<?php

use GitWrapper\GitWrapper;
use Gist\Service\Gist;

$dataPath = $app['settings']['data']['path'];

if ($dataPath[0] !== '/') {
    $app['gist_path'] = $app['root_path'].'/'.$dataPath;
} else {
    $app['gist_path'] = $dataPath;
}

$app['git_wrapper'] = $app->share(function ($app) {
    return new GitWrapper($app['settings']['git']['path']);
});

$app['git_working_copy'] = $app->share(function ($app) {
    return $app['git_wrapper']->init($app['gist_path']);
});

$app['gist'] = $app->share(function ($app) {
    return new Gist($app['gist_path'], $app['git_wrapper'], $app['git_working_copy']);
});
