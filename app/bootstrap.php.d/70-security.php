<?php

use Gist\Service\UserProvider;
use Silex\Provider\SecurityServiceProvider;
use Gist\Service\SaltGenerator;
use Silex\Provider\SessionServiceProvider;
use Gist\Security\AuthenticationProvider;
use Gist\Security\AuthenticationListener;
use Gist\Security\AuthenticationEntryPoint;
use Symfony\Component\Security\Http\HttpUtils;

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


$app['security.authentication_listener.factory.form'] = $app->protect(function ($name, $options) use ($app) {
    $app['security.authentication_provider.'.$name.'.form'] = $app->share(function ($app) {
        return new AuthenticationProvider($app['user.provider']);
    });
    
    $app['security.authentication_listener.'.$name.'.form'] = $app->share(function ($app) use ($name) {
        return new AuthenticationListener(
            $app['security.token_storage'], 
            $app['security.authentication_provider.'.$name.'.form']
        );
    });
   
    return [
        'security.authentication_provider.'.$name.'.form',
        'security.authentication_listener.'.$name.'.form',
        null,
        'pre_auth'
    ];
});

$app->register(
    new SecurityServiceProvider(),
    [
        'security.firewalls' => [
            'default' => [
                'pattern' => '^/',
                'anonymous' => true,
                'form' => [
                    'login_path' => '_login',
                    'check_path' => '/login_check',
                    'always_use_default_target_path' => true,
                    'default_target_path' => '/',
                ],
                'logout' => [
                    'path' => '/logout',
                    'target' => '/',
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
