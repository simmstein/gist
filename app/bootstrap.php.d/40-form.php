<?php

use Silex\Provider\FormServiceProvider;
use Silex\Provider\ValidatorServiceProvider;

$app->register(new FormServiceProvider());
$app->register(new ValidatorServiceProvider());
