<?php

use Silex\Provider\TwigServiceProvider;

$app->register(new TwigServiceProvider(), array(
    'twig.path' => $app['root_path'].'/src/Gist/Resources/views',
));

$app->extend('twig', function ($twig, $app) {
    $base = str_replace($app['request']->server->get('SCRIPT_NAME'), '', $app['request']->getBaseUrl());
    $twig->addGlobal('web_path', $base.'/');

    return $twig;
});
