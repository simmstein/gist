<?php

use Gist\Service\UserProvider;
use Silex\Provider\SecurityServiceProvider;
use Gist\Service\SaltGenerator;
use Silex\Provider\SessionServiceProvider;
use Gist\Security\AuthenticationProvider;
use Gist\Security\AuthenticationListener;
use Gist\Security\AuthenticationEntryPoint;

$app['enable_registration'] = true;
$app['enable_login'] = true;

$app['salt_generator'] = $app->share(function($app) {
    return new SaltGenerator();
});

$app['user.provider'] = $app->share(function ($app) {
    return new UserProvider(
        $app['security.encoder.digest'],
        $app['salt_generator']
    );
});

$app->register(new SessionServiceProvider());


$app['security.authentication_listener.factory.form_login'] = $app->protect(function ($name, $options) use ($app) {
    $app['security.authentication_provider.'.$name.'.form_login'] = $app->share(function ($app) {
        return new AuthenticationProvider($app['user.provider']);
    });
    
    $app['security.authentication_listener.'.$name.'.form_login'] = $app->share(function ($app) use ($name) {
        return new AuthenticationListener(
            $app['security.token_storage'], 
            $app['security.authentication_provider.'.$name.'.form_login']
        );
    });
    
    $app['security.authentication.entry_point.'.$name.'.form_login'] = $app->share(function ($app) use ($name) {
        return new AuthenticationEntryPoint($app['url_generator']);
    });
    
    return [
        'security.authentication_provider.'.$name.'.form_login',
        'security.authentication_listener.'.$name.'.form_login',
        'security.authentication.entry_point.'.$name.'.form_login',
        'pre_auth'
    ];
});

$app->register(
    new SecurityServiceProvider(),
    [
        'security.firewalls' => [
            'default' => [
                'pattern' => '^/[a-z]{2}/',
                'anonymous' => true,
                'http' => false,
                'form_login' => [
                    'login_path' => '/login',
                    'check_path' => '/login_check',
                ],
                'logout' => [
                    'logout_path' => '/logout'
                ],
                'users' => $app->share(function () use ($app) {
                    return $app['user.provider'];
                }),
            ],
        ],
        'security.access_rules' => [
            ['^/[a-z]{2}/my.*$', 'ROLE_USER'],
        ]
    ]
);
