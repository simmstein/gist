<?php

use Gist\Api\Client;

$app['api_client'] = function ($app) {
    return new Client(['base_uri' => 'https://gist.deblan.org/']);
};