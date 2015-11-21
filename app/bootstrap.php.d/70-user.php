<?php

use Gist\Service\UserProvider;
use Silex\Provider\SecurityServiceProvider;
use Gist\Service\SaltGenerator;

$app['salt_generator'] = function ($app) {
    return new SaltGenerator();
};

$app['user.provider'] = function ($app) {
    return new UserProvider(
        $app['security.encoder.digest'], 
        $app['salt_generator']
    );
};

$app->register(
    new SecurityServiceProvider(), 
    [
        'security.firewalls' => [
            'default' => [
                'pattern' => '^/user.*$',
                'anonymous' => false,
                'form' => [
                    'login_path' => '/login', 
                    'check_path' => 'login_check',
                ],
                'logout' => [
                    'logout_path' => '/logout'
                ],
                'users' => $app->share(function() use ($app) {
                    return $app['user.provider'];
                }),
            ],
        ],
        'security.access_rules' => [
            ['^/user.*$', 'ROLE_USER'],
        ]
    ]
);
