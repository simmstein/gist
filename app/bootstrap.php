<?php

require __DIR__.'/../vendor/autoload.php';

/**
 * Wrap everything in a closure to preserve global scope and return the
 * application.
 */
return call_user_func(function () {
    $app = null;

    /**
     * This closure will be used to require other init files with a clean
     * scope, with only access to `$app`.
     */
    $closure = function () use (&$app) {
        require func_get_arg(0);
    };

    $files = array();

    foreach (new DirectoryIterator(__FILE__ . '.d') as $file) {
        if (!$file->isDot() && $file->isFile()) {
            $files[] = $file->getPathname();
        }
    }

    // Sort init files, order is important
    sort($files);

    foreach ($files as $file) {
        $closure($file);
    }

    return $app;
});
