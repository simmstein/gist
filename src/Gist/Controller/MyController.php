<?php

namespace Gist\Controller;

use Symfony\Component\HttpFoundation\Request;
use Gist\Form\DeleteGistForm;
use Gist\Form\FilterGistForm;

/**
 * Class MyController.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class MyController extends Controller
{
    /**
     * "My" page.
     *
     * @param Request $request
     * @param int     $page
     *
     * @return string
     */
    public function myAction(Request $request, $page)
    {
        $page = (int) $page;
        $app = $this->getApp();

        $deleteForm = new DeleteGistForm($app['form.factory'], $app['translator']);
        $deleteForm = $deleteForm->build()->getForm();

        $options = array(
            'type' => 'all',
            'cipher' => 'anyway',
        );

        $filterForm = new FilterGistForm(
            $app['form.factory'],
            $app['translator'],
            $options,
            ['csrf_protection' => false]
        );

        $filterForm = $filterForm->build()->getForm();

        if ($request->query->has('filter')) {
            $filterForm->submit($request);

            if ($filterForm->isValid()) {
                $options = $filterForm->getData();
            }
        }

        $gists = $this->getUser()->getGistsPager($page, $options);

        if ($request->isMethod('post')) {
            $deleteForm->submit($request);

            if ($deleteForm->isValid()) {
                $id = (int) $deleteForm->getData()['id'];

                foreach ($gists as $gist) {
                    if ($gist->getId() === $id) {
                        $gist->delete();
                        $deleted = true;
                        $gists = $this->getUser()->getGistsPager($page, $options);
                    }
                }
            }
        }

        return $this->render(
            'My/my.html.twig',
            array(
                'gists' => $gists,
                'page' => $page,
                'deleteForm' => $deleteForm->createView(),
                'filterForm' => $filterForm->createView(),
                'deleted' => !empty($deleted),
            )
        );
    }
}
