<?php

use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\SessionServiceProvider;

$app->register(new UrlGeneratorServiceProvider());
$app->register(new SessionServiceProvider());
