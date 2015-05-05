<?php

use GitWrapper\GitWrapper;

$app['git'] = function ($app) {
    return new GitWrapper();
};
