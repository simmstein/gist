<?php

$app->error(function (Exception $e, $code) use ($app) {
    return $app['twig']->render(
        'error.html.twig',
        array(
            'code' => $code,
            'name' => get_class($e),
            'exception' => $e,
        )
    );
});
