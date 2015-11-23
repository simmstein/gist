<?php

namespace Gist\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class MyController
 * @author Simon Vieille <simon@deblan.fr>
 */
class MyController extends Controller
{
    public function myAction(Request $request)
    {
        $app = $this->getApp();
    }
}
