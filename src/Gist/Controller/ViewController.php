<?php

namespace Gist\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Gist\Model\GistQuery;

/**
 * Class HomeController
 * @author Simon Vieille <simon@deblan.fr>
 */
class ViewController
{
    public function viewAction(Request $request, Application $app, $gist)
    {
        $gist = GistQuery::create()->findOneByFile($gist);

        if (null === $gist) {
            return $this->notFoundResponse($app);
        }

        return $app['twig']->render(
            'View/view.html.twig',
            array(
                'gist' => $gist,
            )
        );
    }

    protected function notFoundResponse(Application $app)
    {
        return $app['twig']->render(
            'View/notFound.html.twig',
            array(
            )
        );
    }
}
