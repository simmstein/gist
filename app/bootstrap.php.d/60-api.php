<?php

use Gist\Api\Client;

$app['api_client'] = $app->share(function ($app) {
    $client = new Client(['base_uri' => rtrim($app['settings']['api']['base_url'], '/')]);

    if (!empty($app['settings']['api']['client']['api_key'])) {
        $client->setApiKey($app['settings']['api']['client']['api_key']);
    }

    return $client;
});
