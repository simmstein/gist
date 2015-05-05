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
        $data = array(
            'type' => 'xml',
            'cipher' => 'no',
        );

        $form = new CreateGistForm($app['form.factory'], $app['translator'], $data);
        $form = $form->build();

        if ($request->isMethod('post')) {
            $form->submit($request);

            if ($form->isValid()) {

            }
        }

        return $app['twig']->render(
            'Home/index.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }
}
