<?php

namespace Gist\Controller;

use Gist\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

/**
 * Class MyController
 * @author Simon Vieille <simon@deblan.fr>
 */
class MyController extends Controller
{
    public function myAction(Request $request, Application $app)
    {
        echo '<pre>', var_dump($this->getUser($app)), '</pre>';
        die;
    }
}

