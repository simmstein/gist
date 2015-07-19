<?php

use Gist\Api\Client;

$app['api_client'] = function ($app) {
    return new Client(['base_uri' => 'http://127.0.0.1:8080/']);
};
