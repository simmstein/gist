<?php

namespace Gist\Controller;

use Symfony\Component\HttpFoundation\Request;
use Gist\Form\CreateGistForm;
use Gist\Form\CloneGistForm;
use Gist\Model\Gist;
use GitWrapper\GitException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\FormError;

/**
 * Class EditController.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class EditController extends Controller
{
    /**
     * Creation page.
     *
     * @param Request $request
     *
     * @return string
     */
    public function createAction(Request $request)
    {
        $app = $this->getApp();

        $data = array(
            'type' => 'html',
            'cipher' => 'no',
        );

        $form = new CreateGistForm($app['form.factory'], $app['translator'], $data);
        $form = $form->build()->getForm();

        if ($request->isMethod('post')) {
            $form->submit($request);
            $data = $form->getData();

            if (empty($form->getData()['content']) && !$request->files->has('file')) {
                $form->get('content')->addError(new FormError($app['translator']->trans('form.error.not_blank')));
            } elseif (empty($form->getData()['content']) && $request->files->has('file')) {
                if (count($form->get('file')->getErrors()) === 0) {
                    $data['content'] = file_get_contents($form->get('file')->getData()->getPathName());
                    unset($data['file']);
                }
            }

            if ($form->isValid()) {
                $gist = $app['gist']->create(new Gist(), $data, $this->getUser());
            }
        }

        return $this->render(
            'Edit/index.html.twig',
            array(
                'gist' => isset($gist) ? $gist : null,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Cloning page.
     *
     * @param Request $request
     *
     * @return string
     */
    public function cloneAction(Request $request, $gist, $commit)
    {
        $app = $this->getApp();

        $viewOptions = $this->getViewOptions($request, $gist, $commit);

        $data = array(
            'type' => $viewOptions['gist']->getType(),
            'content' => $viewOptions['raw_content'],
            'cipher' => 'no',
        );

        $form = new CloneGistForm($app['form.factory'], $app['translator'], $data);
        $form = $form->build()->getForm();

        if ($request->isMethod('post')) {
            $form->submit($request);

            if ($form->isValid()) {
                try {
                    $gist = $app['gist']->commit($viewOptions['gist'], $form->getData());
                } catch (GitException $e) {
                    $gist = $viewOptions['gist'];
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

        return $this->render('Edit/clone.html.twig', $viewOptions);
    }
}
