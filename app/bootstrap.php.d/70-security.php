<?php

use Gist\Service\UserProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\RememberMeServiceProvider;
use Gist\Service\SaltGenerator;
use Gist\Security\AuthenticationProvider;
use Gist\Security\AuthenticationListener;
use Gist\Security\LogoutSuccessHandler;
use Silex\Provider\SessionServiceProvider;

$app['enable_registration'] = true;
$app['enable_login'] = true;
$app['login_required_to_edit_gist'] = false;
$app['login_required_to_view_gist'] = false;
$app['login_required_to_view_embeded_gist'] = false;

$app['token'] = 'ThisTokenIsNotSoSecretChangeIt';

$app['salt_generator'] = $app->share(function ($app) {
    return new SaltGenerator();
});

$app['user.provider'] = $app->share(function ($app) {
    return new UserProvider(
        $app['security.encoder.digest'],
        $app['salt_generator']
    );
});

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
        'pre_auth',
    ];
});

$firewall = [
    'security.firewalls' => [
        'default' => [
            'pattern' => '^/',
            'anonymous' => true,
            'form' => [
                'login_path' => '_login',
                'check_path' => '/login_check',
                'always_use_default_target_path' => false,
                'default_target_path' => '/',
            ],
            'logout' => [
                'path' => '/logout',
            ],
            'users' => $app->share(function () use ($app) {
                return $app['user.provider'];
            }),
            'remember_me' => [
                'key' => $app['token'],
                'path' => '/',
                'always_remember_me' => false,
            ],
        ],
    ],
    'security.access_rules' => [
        ['^/[a-z]{2}/my.*$', 'ROLE_USER'],
    ],
];

if ($app['login_required_to_edit_gist'] || $app['login_required_to_view_gist'] || $app['login_required_to_view_embeded_gist']) {
    $exceptedUriPattern = ['login', 'register'];

    if ($app['login_required_to_view_gist'] === true) {
        $firewall['security.access_rules'][] = ['^/[a-z]{2}/view.*$', 'ROLE_USER'];
        $firewall['security.access_rules'][] = ['^/[a-z]{2}/revs.*$', 'ROLE_USER'];
    } else {
        $exceptedUriPattern[] = 'view';
        $exceptedUriPattern[] = 'revs';
    }

    if ($app['login_required_to_view_embeded_gist'] === true) {
        $firewall['security.access_rules'][] = ['^/[a-z]{2}/embed.*$', 'ROLE_USER'];
    } else {
        $exceptedUriPattern[] = 'embed';
    }

    if ($app['login_required_to_edit_gist'] === true) {
        $firewall['security.access_rules'][] = ['^/[a-z]{2}/(?!('.implode('|', $exceptedUriPattern).')).*$', 'ROLE_USER'];
    }
}

$app->register(new SecurityServiceProvider(), $firewall);
$app->register(new SessionServiceProvider());
$app->register(new RememberMeServiceProvider());

$app['security.authentication.logout_handler._proto'] = $app->protect(function ($name, $options) use ($app) {
    return $app->share(function () use ($name, $options, $app) {
        return new LogoutSuccessHandler(
            $app['security.http_utils'],
            isset($options['target_url']) ? $options['target_url'] : '/'
        );
    });
});
