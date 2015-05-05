<?php

use Silex\Provider\TwigServiceProvider;

$app->register(new TwigServiceProvider(), array(
    'twig.path' => $app['root_path'].'/src/Gist/Resources/views',
));

$app->extend('twig', function ($twig, $app) {
    $twig->addGlobal('web_path', '/');

    return $twig;
});

