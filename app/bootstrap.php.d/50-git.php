<?php

use GitWrapper\GitWrapper;

$app['git'] = function ($app) {
	echo "ok";
    return new GitWrapper();
};

