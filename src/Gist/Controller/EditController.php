<?php

namespace Gist\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Gist\Form\CreateGistForm;
use Gist\Model\Gist;

/**
 * Class HomeController
 * @author Simon Vieille <simon@deblan.fr>
 */
class EditController extends Controller
{
    public function createAction(Request $request, Application $app)
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
                $gist = $app['gist']->create(new Gist(), $form->getData());
            }
        }

        return $app['twig']->render(
            'Home/index.html.twig',
            array(
                'gist' => isset($gist) ? $gist : null,
                'form' => $form->createView(),
            )
        );
    }
    
	public function cloneAction(Request $request, Application $app, $gist, $commit)
    {
        $viewOptions = $this->getViewOptions($request, $app, $gist, $commit);

        if (is_array($viewOptions)) {
            return $app['twig']->render('View/view.html.twig', $viewOptions);
        } else {
            return $this->notFoundResponse($app);
        }
    }
}
