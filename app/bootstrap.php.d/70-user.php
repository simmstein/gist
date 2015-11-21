<?php

use Gist\Service\UserProvider;
use Silex\Provider\SecurityServiceProvider;
use Gist\Service\SaltGenerator;
use Silex\Provider\SessionServiceProvider;
use Gist\Security\AuthentificationProvider;
use Gist\Security\AuthentificationListener;

$app['salt_generator'] = function ($app) {
    return new SaltGenerator();
};

$app['user.provider'] = function ($app) {
    return new UserProvider(
        $app['security.encoder.digest'],
        $app['salt_generator']
    );
};

$app->register(new SessionServiceProvider());


$app['security.authentication_listener.factory.form_login'] = $app->protect(function ($name, $options) use ($app) {
    $app['security.authentication_provider.'.$name.'.form_login'] = $app->share(function ($app) {
        return new AuthentificationProvider($app['user.provider']);
    });
    
    $app['security.authentication_listener.'.$name.'.form_login'] = $app->share(function ($app) {
        return new AuthentificationListener(
            $app['security.token_storage'], 
            $app['security.authentication_manager'],
            $app['url_generator']
        );
    });
    
    return [
        'security.authentication_provider.'.$name.'.form_login',
        'security.authentication_listener.'.$name.'.form_login',
        null,
        'pre_auth'
    ];
});

$app->register(
    new SecurityServiceProvider(),
    [
        'security.firewalls' => [
            'default' => [
                'pattern' => '^/[a-z]{2}/my',
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
