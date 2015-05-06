<?php

use Silex\Provider\TwigServiceProvider;

$app->register(new TwigServiceProvider(), array(
    'twig.path' => $app['root_path'].'/src/Gist/Resources/views',
));

$app->extend('twig', function ($twig, $app) {
    $twig->addGlobal('web_path', $app['request']->getBaseUrl().'/');

    return $twig;
});

$app['geshi'] = function ($app) {
    $geshi = new GeSHi();
    $geshi->enable_classes();
    $geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);

    return $geshi;
};
