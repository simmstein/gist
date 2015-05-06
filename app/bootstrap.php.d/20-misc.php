<?php

use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Propel\Runtime\Propel;

$app->register(new UrlGeneratorServiceProvider());
$app->register(new SessionServiceProvider());

Propel::init('app/config/propel/config.php');
