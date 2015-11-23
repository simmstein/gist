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

$app['geshi'] = $app->share(function ($app) {
    $geshi = new GeSHi();
    $geshi->enable_classes();
    $geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);

    return $geshi;
});
