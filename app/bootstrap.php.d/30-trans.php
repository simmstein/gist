<?php

use Silex\Provider\TranslationServiceProvider;
use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\Loader\YamlFileLoader;

$app->register(new TranslationServiceProvider(), array(
    'locale' => 'en',
    'locale_fallback' => 'en',
    'locales' => array('en', 'fr'), // Custom parameter, not Silex
));

$app['translator'] = $app->extend('translator', function ($translator, $app) {
    $translator->addLoader('yaml', new YamlFileLoader());

    foreach ($app['locales'] as $locale) {
        $file = $app['root_path'].'/app/locales/'.$locale.'.yml';

        if (is_file($file)) {
            $translator->addResource('yaml', $file, $locale);
        }
    }

    return $translator;
});

$app['routes'] = $app->share($app->extend('routes', function ($routes, $app) {
    $routes->addPrefix('/{_locale}');
    $routes->addDefaults(array('_locale' => $app['locale_fallbacks'][0]));
    $routes->addRequirements(array('_locale' => implode('|', $app['locales'])));

    return $routes;
}));

/**
 * Redirect home on right locale page, regarding of request accept locale or
 * default fallback.
 */
$app->get('/', function (Request $request) use ($app) {
    $accept = AcceptHeader::fromString($request->headers->get('Accept-Language'));

    // Default locale fallback
    $foundLocale = $app['translator']->getLocale();

    foreach ($app['locales'] as $locale) {
        if ($accept->has($locale)) {
            $foundLocale = $locale;
            break;
        }
    }

    return new RedirectResponse($app['url_generator']->generate(
        'home',
        array('_locale' => $foundLocale)
    ));
});
