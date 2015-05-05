<?php

namespace Gist\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Gist\Form\CreateGistForm;

/**
 * Class HomeController
 * @author Simon Vieille <simon@deblan.fr>
 */
class HomeController
{
    public function indexAction(Request $request, Application $app)
    {
        $form = new CreateGistForm($app['form.factory'], $app['translator']);
		$form = $form->build();

        return $app['twig']->render(
            'Home/index.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }
}
