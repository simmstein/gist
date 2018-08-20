<?php

use Silex\Provider\HttpCacheServiceProvider;

$app->register(new HttpCacheServiceProvider(), array(
    'http_cache.cache_dir' => $app['root_path'].'/cache/',
    'http_cache.esi' => null,
));
