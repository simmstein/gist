<?php

namespace Gist\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Gist\Form\CreateGistForm;
use Gist\Form\CloneGistForm;
use Gist\Model\Gist;
use GitWrapper\GitException;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
            'Edit/index.html.twig',
            array(
                'gist' => isset($gist) ? $gist : null,
                'form' => $form->createView(),
            )
        );
    }

    public function cloneAction(Request $request, Application $app, $gist, $commit)
    {
        $viewOptions = $this->getViewOptions($request, $app, $gist, $commit);

        $data = array(
            'type' => $viewOptions['gist']->getType(),
            'content' => $viewOptions['raw_content'],
            'cipher' => 'no',
        );

        $form = new CloneGistForm($app['form.factory'], $app['translator'], $data);
        $form = $form->build();

        if ($request->isMethod('post')) {
            $form->submit($request);

            if ($form->isValid()) {
                try {
                    $gist = $app['gist']->commit($viewOptions['gist'], $form->getData());
                } catch (GitException $e) {

                }

                $history = $app['gist']->getHistory($gist);

                return new RedirectResponse($app['url_generator']->generate(
                    'view',
                    array(
                        'gist' => $gist->getFile(),
                        'commit' => array_pop($history)['commit'],
                    )
                ));
            }
        }

        $viewOptions['form'] = $form->createView();

        return $app['twig']->render('Edit/clone.html.twig', $viewOptions);
    }
}
