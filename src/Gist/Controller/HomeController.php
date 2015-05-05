<?php

namespace Gist\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class HomeController
 * @author Simon Vieille
 */
class HomeController
{
    public function indexAction(Request $request, Application $app)
    {
        return $app['twig']->render('Home/index.html.twig');
    }
}
